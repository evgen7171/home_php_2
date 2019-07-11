<?php

/**
 * Class Product - класс продукт
 * id, name, price - свойства класса
 * при создании объекта задаются id, name, price; sale=0
 * setSale - метод задания sale
 * getProperties - получить все свойства объекта продукт
 */
class Product
{
    public $id;
    public $name;
    public $price;
    public $sale;

    public function __construct($id, $name, $price)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->sale = 0;
    }

    public function setSale($sale)
    {
        $this->sale = $sale;
    }

    public function getProperties()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'sale' => $this->sale
        ];
    }
}

/**
 * Class ProductBox - класс продукты
 * своство $products - массив продуктов
 * метод getKeyProduct, недоступный в наследном классе и извне, - вспомогательный метод
 * метод init() - инициализация
 * метод addNewProduct() - добавление продукта (должен быть в виде массива)
 * метод addProduct() - добавление существующего продукта по id
 * метод removeProduct() - удаление существующего продукта по id
 * метод changeCountProduct() - установление количества (count) существующего продукта по id
 */
class ProductBox
{
    public $products;

    public function __construct()
    {
        $this->products = [];
    }

    private function getKeyProduct($id)
    {
        foreach ($this->products as $key => $item) {
            if ($item['id'] === $id) {
                return $key;
            }
        }
        return NULL;
    }

    public function init()
    {
        $prod1 = new Product(12, 'chair', 209);
        $prod2 = new Product(123, 'table', 411);
        $products = [
            $prod1->getProperties(),
            $prod2->getProperties()

        ];
        $this->addNewProducts($products);
    }

    public function findProduct($id)
    {
        $key = $this->getKeyProduct($id);
        return $this->products[$key];
    }

    public function changeCountProduct($product_id, $count)
    {
        if ($count > 0) {
            $key = $this->getKeyProduct($product_id);
            $this->products[$key]['count'] = $count;
        } else if (!$count) {
            $key = $this->getKeyProduct($product_id);
            array_splice($this->products, $key, 1);
        } else {
            return 'error';
        }
    }

    public function addNewProduct($product)
    {
        array_push($this->products, [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'sale' => $product['sale'],
            'count' => 1
        ]);
    }

    public function addNewProducts($products)
    {
        foreach ($products as $product) {
            $this->addNewProduct($product);
        }
    }

    public function addProduct($id)
    {
        $key = $this->getKeyProduct($id);
        if (!is_null($key)) {
            $this->products[$key]['count']++;
        }
    }

    public function removeProduct($id)
    {
        $key = $this->getKeyProduct($id);
        if ($this->products[$key]['count'] > 1) {
            $this->products[$key]['count']--;
        } else {
            array_splice($this->products, $key, 1);
        }
    }
}

$product_box = new ProductBox();

/**
 * Class Cart - класс корзины, наследуется от класса продуктов
 * инициализация наследного класса отличается от от инициализации родителя (метода init() )
 */
class Cart extends ProductBox
{
    public function init()
    {
        $this->products = [];
    }
}

$cart = new Cart();

/**
 * Class ProductBox_DB - класс продукты с работой в базе данных
 * свойства: $db_name - имя базы данных, $table - имя таблицы в базе данных
 * метод  get_from_query_db - для отправки запроса к базе данных и получения ассоциированного массива данных
 * getProducts - получить все продукты
 * findProduct - найти данные о продукте по id
 */
class ProductBox_DB
{
    public $db_name;
    public $table = 'products';

    public function __construct($db_name)
    {
        $this->db_name = $db_name;
    }

    public function get_from_query_db($query)
    {
        $link = mysqli_connect(
            'localhost',
            'root',
            '',
            $this->db_name
        );
        if ($link) {
            $result = mysqli_query($link, $query) or die("ERROR: " . mysqli_error($link));
            if (!is_bool($result)) {
                $arr = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $arr[] = $row;
                }
                return $arr;
            } else {
                return ['message' => "data base query is " . ($result ? 'true' : 'false')];
            }
        } else {
            return ['error' => 'error of access to data base'];
        }
    }

    public function getProducts()
    {
        $query = 'SELECT * FROM ' . $this->table;
        $products = $this->get_from_query_db($query);
        return $products;
    }

    public function findProduct($id)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE id = ' . $id;
        return $this->get_from_query_db($query);
    }

    public function get_columns_from_db()
    {
        $query = 'SELECT * FROM ' . $this->table;
        $link = mysqli_connect(
            'localhost',
            'root',
            '',
            $this->db_name
        );
        if ($link) {
            $result = mysqli_query($link, $query) or die("ERROR: " . mysqli_error($link));
            if (!is_bool($result)) {

                $arr = [];
                $columns = mysqli_fetch_fields($result);
                foreach ($columns as $column) {
                    if ($column->name !== 'id') {
                        $name = $column->name;
                        switch ($column->type) {
                            case 3:
                                $type = 'INT';
                                break;
                            case 253:
                                $type = 'VARCHAR(45)';
                                break;
                        }
                        array_push($arr, [
                            'name' => $name,
                            'type' => $type
                        ]);
                    }
                }
                return $arr;

            } else {
                return ['message' => "data base query is " . ($result ? 'true' : 'false')];
            }
        } else {
            return ['error' => 'error of access to data base'];
        }

    }
}

/**
 * Class Cart_DB - класс корзины с работой через базу данных, наследуется от класса продукты
 * метод init - инициализация: проверка существует ли такая таблица, если нет - создает
 * метод create_table - создать таблицу [cart]
 * метод changeCountProduct - установить количество конкретного продукта по id
 * метод addNewProduct - добавление продукта, которого нет в базе данных
 * метод addProduct - увеличение количества продукта на 1 по id
 * метод removeProduct - уменьшение количества продукта на 1 по id, или удаление его из базы данных
 */
class Cart_DB extends ProductBox_DB
{
    public function __construct($db_name, $table = 'cart', $table_products = 'products')
    {
        parent::__construct($db_name);
        $this->table = $table;
        $this->table_products = $table_products;

        $this->init();
    }

    function is_table_exist()
    {
        $link = mysqli_connect(
            'localhost',
            'root',
            '',
            $this->db_name
        );
        if ($link) {
            $query = 'SELECT * FROM ' . $this->table;
            $result = mysqli_query($link, $query);
            return (bool)$result;
        } else {
            return ['error' => 'error of access to data base'];
        }
    }

    function init()
    {
        if (!$this->is_table_exist()) {
            $product_box = new ProductBox_DB($this->db_name);
            $columns = $product_box->get_columns_from_db();
            $this->create_table($columns);
        };
    }

    function create_table($columns)
    {
        $str_for_query = '';
        foreach ($columns as $column) {
            $str_for_query .= "`" . $column['name'] . "` " . $column['type'] . " NULL ,";
        }
        $query = "
                CREATE  TABLE `$this->table` (
                `id` INT NOT NULL AUTO_INCREMENT ,
                $str_for_query
                `count` INT NULL ,
                PRIMARY KEY (`id`) );";

        $this->get_from_query_db($query);
    }

    public function changeCountProduct($product_id, $count)
    {
        $query = 'UPDATE ' . $this->table . ' SET count=' . $count . ' WHERE id=' . $product_id;
        return $this->get_from_query_db($query);
    }

    public function addNewProduct($product)
    {
        if (empty($this->findProduct($product['id']))) {
            $product_local = $product;
            if (!isset($product_local['sale'])) {
                $product_local['sale'] = 0;
            }
            if (!isset($product_local['count'])) {
                $product_local['count'] = 1;
            }
            $keys_str = '';
            $values_str = '';
            for ($i = 0; $i < count($product_local); $i++) {
                $keys_str .= "`" . array_keys($product_local)[$i] . "`";
                $values_str .= "'" . array_values($product_local)[$i] . "'";
                if ($i < count($product_local) - 1) {
                    $keys_str .= ', ';
                    $values_str .= ', ';
                }
            }
            $query = 'INSERT INTO `' . $this->table . '` (' . $keys_str . ') VALUES (' . $values_str . ')';
            return $this->get_from_query_db($query);
        } else {
            return 'this products already exist in data base';
        }
    }

    public function addProduct($id)
    {
        $query = "SELECT count FROM $this->table WHERE id = $id";
//        $query = "SELECT count FROM $this->table WHERE id = $id LIMIT 1";
        $count = $this->get_from_query_db($this->db_name, $query)[0]['count'];
        $query = "UPDATE `$this->table` SET `count`='" . ($count + 1) . "' WHERE `id`='$id'";
        return $this->get_from_query_db($query);
    }

    public function removeProduct($id)
    {
        $query = "SELECT count FROM $this->table WHERE id = $id";
//        $query = "SELECT count FROM $this->table WHERE id = $id LIMIT 1";
        $count = $this->get_from_query_db($this->db_name, $query)[0]['count'];
        if ($count > 1) {
            $query = "UPDATE $this->table SET count=" . ($count - 1) . " WHERE id= $id";
        } else {
            $query = "DELETE FROM $this->table WHERE id= $id";
        }
        return $this->get_from_query_db($query);
    }
}


class A
{
    public function foo()
    {
        static $x = 0;
        echo ++$x;
    }
}

$a1 = new A();
$a2 = new A();
$a1->foo();
$a2->foo();
$a1->foo();
$a2->foo();

echo '<br>';
echo
'Код выдает значение статичной переменной
(которая существует и опеределена для класса А).
При каждом вызове метода foo() срабатывает пре-инкремент.
Строка static $x = 0; срабатывает только один первый раз,
 при первом вызове класса А
';

echo '<br>';
echo '<br>';


class A1
{
    public function foo()
    {
        static $x = 0;
        echo ++$x;
    }
}

class B1 extends A1
{
}

$a1 = new A1();
$b1 = new B1();
$a1->foo();
$b1->foo();
$a1->foo();
$b1->foo();

echo '<br>';
echo
'Код выдает значение статичной переменной
(которая существует и опеределена для класса А).
При каждом вызове метода foo() срабатывает пре-инкремент.
Строка static $x = 0; срабатывает только один первый раз,
 при первом вызове класса А1. Для наследованного класса (B) все то же самое, 
 только за исключением, что $x уже свой - потому и инициализируется при 
 первом вызове класса B1
';

echo '<br>';
echo '<br>';


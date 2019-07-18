<?php

use  \App\models\{User, Good};
use  \App\services\BD;

include $_SERVER['DOCUMENT_ROOT'] .
    '/../services/Autoload.php';

spl_autoload_register(
    [new Autoload(),
        'loadClass']
);

$user = new User();
$good = new Good();

function changeObj($obj, $id, $arr)
{
    var_dump($arr);
    $obj->addOne(12, $arr);

    $props = $obj->getProperties();
    foreach ($props as $prop) {
        if ($prop !== 'id') {
            $arr[$prop] = $_POST[$prop];
        }
    }
    if (!($id > 0)) {
        $obj->addOne($arr);
    } else {
        $obj->saveOne($id, $arr);
    }
}

function checkObj($obj)
{
    $objName = null;
    if (in_array('password', $obj->getProperties())) {
        $objName = 'user';
    } else if (in_array('price', $obj->getProperties())) {
        $objName = 'good';
    }
    return $objName;
}

function renderFormAdd($obj)
{
    $objName = checkObj($obj);
    $str = sprintf('<br>
                    <form action="index.php" method="post">
                        <div style="display: flex;flex-direction: column; width: 120px">');
    $props = $obj->getProperties();
    foreach ($props as $prop) {
        $str .= sprintf('
            <label>%s =
                <input type="text" name="%s" value="%s">
            </label>',
            $prop, $prop, $prop === 'id' ? '0' : '');
    }
    $str .= sprintf('<input type="hidden" name="oper" value="new_%s">
                            <input type="submit" value="OK">
                        </div>
                    </form>', $objName);
    echo $str;
}

function delete($obj, $id)
{
    $info = $obj->getOne($id);
    var_dump($info);
    $obj->deleteOne($id);
    echo $info ? 'Удалено успешно' : 'Такого id нет';
}

function getObjectWithoutProperty($obj, $prop)
{
    $result = [];
    foreach ($obj as $key => $value) {
        if ($key !== $prop) {
            $result[$key] = $value;
        }
    }
    return $result;
}

function render($user, $good)
{
    $id = 0;
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
    }
    if (isset($_POST['oper'])) {
        $oper = $_POST['oper'];
        if ($oper === 'new_user') {
            changeObj($user, $id, getObjectWithoutProperty($_POST, 'oper'));
        } else if ($oper === 'new_product') {
            changeObj($good, $id, getObjectWithoutProperty($_POST, 'oper'));
        }
    }
    if (isset($_POST['submit']) && !($id > 0)) {
        switch ($_POST['submit']) {
            case 'Вывести < users >':
                var_dump($user->getAll());
                break;
            case 'Вывести < products >':
                var_dump($good->getAll());
                break;
            case 'Очистить':
                break;
            case 'Добавить/изменить < user >':
                renderFormAdd($user);
                break;
            case 'Добавить/изменить < product >':
                renderFormAdd($good);
                break;
        }
    } else if (isset($_POST['submit']) && ($id > 0)) {
        switch ($_POST['submit']) {
            case 'Вывести < users >':
                var_dump($user->getOne($id));
                break;
            case 'Вывести < products >':
                var_dump($good->getOne($id));
                break;
            case 'Очистить':
                break;
            case 'Удалить < user > с данным id':
                delete($user, $id);
                break;
            case 'Удалить < product > с данным id':
                delete($good, $id);
                break;
        }
    }
}

function firstForm()
{
    echo <<<render
    
    <form action="index.php" method="post">
        <label>id=<input type="text" name="id" value="0"></label>
        <div>
<!--            <input type="submit" name="submit" value="Добавить/изменить < user >">-->
<!--            <input type="submit" name="submit" value="Добавить/изменить < product >">-->
        </div>
        <div>
            <input type="submit" name="submit" value="Удалить < user > с данным id">
            <input type="submit" name="submit" value="Удалить < product > с данным id">
        </div>
        <div>
            <input type="submit" name="submit" value="Вывести < users >">
            <input type="submit" name="submit" value="Вывести < products >">
            <input type="submit" name="submit" value="Очистить">
        </div>
    </form>
        
render;
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<?php
firstForm();
render($user, $good);
?>


</body>
</html>






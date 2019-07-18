<?php

namespace App\services;

use App\traits\TSingleton;

/**
 * Class BD - класс для работы с базой данных (pdo)
 * @package App\services
 */
class BD implements IBD
{
    /**
     * доваление уникальности классу
     */
    use TSingleton;

    private $config = [
        'user' => 'root',
        'pass' => '',
        'driver' => 'mysql',
        'bd' => 'gbphp',
        'host' => 'localhost:3307',
        'charset' => 'UTF8',
    ];

    private $currentId = null;

    /**
     * @var \PDO|null
     */
    protected $connect = null;

    /**
     * Возвращает только один коннект с базой - объект PDO,
     * функция getConnect - является "подготовкой" запроса
     * т.е. возвращает объект, который обладает
     * спец методами для отправки запроса
     * (..prepare - подготовка,
     * ..excute - исполнение запроса)
     *
     * @return \PDO|null
     */
    protected function getConnect()
    {
        if (empty($this->connect)) {
            $this->connect = new \PDO(
                $this->getDSN(),
                $this->config['user'],
                $this->config['pass']
            );
            $this->connect->setAttribute(
                \PDO::ATTR_DEFAULT_FETCH_MODE,
                \PDO::FETCH_ASSOC
            );
        }
        return $this->connect;
    }

    /**
     * Установить текущий id
     * @param $id
     */
    public function setCurrentId($id)
    {
        $this->currentId = $id;
    }

    /**
     * Получить текущий id
     * @return |null
     */
    public function getCurrentId()
    {
        return $this->currentId;
    }

    /**
     * Создание строки формата
     *  'mysql:host=localhost;dbname=DB;charset=UTF8'
     * - настройки для подключения
     * (driver - что будет обрабатывать запрос, mysql;
     * host - хост;
     * dbname/bd - имя базы данных;
     * charset - кодировка)
     * @return string
     */
    private function getDSN()
    {
        return sprintf(
            '%s:host=%s;dbname=%s;charset=%s',
            $this->config['driver'],
            $this->config['host'],
            $this->config['bd'],
            $this->config['charset']
        );
    }

    /**
     * Выполнение запроса
     *
     * @param string $sql 'SELECT * FROM users WHERE id = :id'
     * @param array $params [':id' => 123]
     * @return \PDOStatement
     */
    private function query(string $sql, array $params = [])
    {
        $PDOStatement = $this->getConnect()->prepare($sql);
        $PDOStatement->execute($params);
        return $PDOStatement;
    }

    /**
     * Получение одной строки
     *
     * @param string $sql
     * @param array $params
     * @return array|mixed
     */
    public function find(string $sql, array $params = [])
    {
        return $this->query($sql, $params)->fetch();
    }

    /**
     * Получение всех строк
     *
     * @param string $sql
     * @param array $params
     * @return mixed
     */
    public function findAll(string $sql, array $params = [])
    {
        $query = $this->query($sql, $params);
        return $query->fetchAll();
    }

    /**
     * @param string $sql
     * @param array $params
     * @return mixed
     */
    public function findObject(string $sql, array $params = [])
    {
        $query = $this->query($sql, $params);
        return $query->fetchObject();
    }

    /**
     * @param string $sql
     * @param array $params
     * @return mixed
     */
    public function findAllObject(string $sql, array $params = [])
    {
        $arr = [];
        $query = $this->query($sql, $params);
        do {
            $obj = $query->fetchObject();
            array_push($arr, $obj);
        } while ($obj);
        array_pop($arr);
        return $arr;
    }

    /**
     * Выполнение безответного запроса
     *
     * @param string $sql
     * @param array $params
     */
    public function execute(string $sql, array $params = [])
    {
        $this->query($sql, $params);
    }

    /**
     * Метод получения строки из частей (ключ=значение)
     * @param $arr - массив из частей (ключ=значение)
     * @return string
     */
    public function getStringFromRowsArray_set($arr, $mode = 'set')
    {
        $str = '';
        if ($mode === 'set') {
            for ($i = 0; $i < count($arr); $i++) {
                $str .= sprintf("%s=`%s`",
                    array_keys($arr)[$i],
                    array_values($arr)[$i]
                );
                if ($i < count($arr) - 1) {
                    $str .= ', ';
                }
            }
        } else if ($mode === 'add') {
            $keys_str = '';
            $values_str = '';
            for ($i = 0; $i < count($arr); $i++) {
                $keys_str .= array_keys($arr)[$i];
                $values_str .= sprintf("'%s'", array_values($arr)[$i]);
                if ($i < count($arr) - 1) {
                    $keys_str .= ', ';
                    $values_str .= ', ';
                }
            }
            $str = sprintf("(%s) VALUES (%s)", $keys_str, $values_str);
        }
        return $str;
    }

}
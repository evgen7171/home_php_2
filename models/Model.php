<?php

namespace App\models;

use App\services\BD;
use App\traits\TGetSetProperties;

abstract class Model
{
    /**
     * @var BD Класс для работы с базой данных
     */
    protected $bd;

    /**
     * Model constructor.
     */
    public function __construct()
    {
        $this->bd = BD::getInstance();
    }

    /**
     * Данный метод должен вернуть название таблицы
     * @return string
     */
    abstract protected function getTableName();

    /**
     * Возращает запись с указанным id
     *
     * @param int $id ID Записи таблицы
     * @return array
     */
    public function getOne($id)
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} WHERE id = :id";
        $this->bd->setCurrentId($id);
        return $this->bd->find($sql, [':id' => $id]);
    }

    /**
     * Получить значение опеределенного свойства (из таблицы базы данных)
     * @param $id
     * @param $prop
     * @return mixed
     */
    protected function getValueProperty($id, $prop)
    {
        $tableName = $this->getTableName();
        $sql = "SELECT $prop FROM {$tableName} WHERE id = :id";
        $this->bd->setCurrentId($id);
        return $this->bd->find($sql, [':id' => $id]);
    }

    /**
     * Установить значение опеределенного свойства (из таблицы базы данных)
     * @param $id
     * @param $prop
     * @param $value
     * @return mixed
     */
    protected function setValueProperty($id, $prop, $value)
    {
        $tableName = $this->getTableName();
        $sql = "UPDATE {$tableName} SET $prop=`$value` WHERE id = :id;";
        $this->bd->setCurrentId($id);
        return $this->bd->find($sql, [':id' => $id]);
    }

    public function getLastId()
    {
        $tableName = $this->getTableName();
        $sql = "SELECT MAX(`id`) AS last_id FROM {$tableName}";
        return $this->bd->find($sql)['last_id'];
    }

    public function deleteOne($id)
    {
        if (!$this->getOne($id)) {
            return 'id does not exist';
        }

        $tableName = $this->getTableName();
        $sql = "DELETE FROM {$tableName} WHERE id = :id;";
        $this->bd->setCurrentId($this->getLastId());
        return $this->bd->find($sql, [':id' => $id]);
    }

    /**
     * @param $arr
     * @param $str
     * @return string
     */
    ///////////////////////////////

    /**
     * getStringFromArray - для получения строки из массива
     * @param $arr
     * @param $str
     * @return string
     */

      public function getStringFromRowsArray($arr, $str)
    {
        return $this->bd->getStringFromRowsArray_set($arr, $str);
    }

    ///////////////////////////////

    /**
     * Установить в базе данных под определенным id значения
     * @param $id - идентификатор
     * @param $arr - массив состоящий из пар "ключ-значение"
     * @return array|mixed
     */
    public function setOne($id, $arr)
    {
        $str = $this->getStringFromRowsArray($arr, 'set');
        $tableName = $this->getTableName();
        $sql = "UPDATE {$tableName} SET $str WHERE id = :id;";
        $this->bd->setCurrentId($id);
        return $this->bd->find($sql, [':id' => $id]);
    }

    /**
     * Добавить в базе данных строку с данными
     * @param $arr - массив состоящий из пар "ключ-значение"
     * @return array|mixed
     */
    public function addOne($arr)
    {
        $str = $this->getStringFromRowsArray($arr, 'add');
        $tableName = $this->getTableName();
        $sql = "INSERT INTO {$tableName} $str;";
        $this->bd->setCurrentId($this->getLastId());
        return $this->bd->find($sql);
    }

    /**
     * Добавляет/изменяет данные в зависимости существует ли id в базе данных
     * @param $id - идентификатор
     * @param $arr - массив состоящий из пар "ключ-значение"
     * @return array|mixed
     */
    public function saveOne($id, $arr)
    {
        if ($this->getOne($id)) {
            return $this->setOne($id, $arr);
        }
        return $this->addOne($arr);
    }

    /**
     * Возращает запись с указанным id
     *
     * @param int $id ID Записи таблицы
     * @return array
     */
    public function getOneObject($id)
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} WHERE id = :id";
        $this->bd->setCurrentId($id);
        return $this->bd->findObject($sql, [':id' => $id]);
    }

    /**
     * Получение всех записей таблицы
     * @return mixed
     */
    public function getAll()
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} ";
        return $this->bd->findAll($sql);
    }

    /**
     * Получение всех записей таблицы в виде массива объектов
     * @return mixed
     */
    public function getAll_object()
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} ";
        return $this->bd->findAllObject($sql);
    }

}

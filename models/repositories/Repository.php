<?php

namespace App\models\repositories;

use App\models\entities\Entity;
use App\services\BD;

/**
 * Class Model
 * @package App\models
 *
 * @property int $id
 */
abstract class Repository
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
     * метод, который возвращает имя сущности
     * @return mixed
     */
    abstract protected function getEntityName();

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
        return $this->bd->queryObject(
            $sql,
            $this->getEntityName(),
            [':id' => $id]
        );
    }

    /**
     * Получение всех записей таблицы
     * @return mixed
     */
    public function getAll()
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} ";
        return $this->bd->queryObjects($sql, $this->getEntityName());
    }


    /**
     * метод получения данных для просмотра
     * @param $id
     * @return array
     */
    public function getDataOne($id)
    {
        $result = [];
        $object = $this->getOne($id);
        $ClassName = (new $this)->getEntityName();
        $properties = (new $ClassName)->getProperties();
        if ($object) {
            foreach ($object as $key => $value) {
                if (gettype($key) == 'integer' || !in_array($key, $properties)) {
                    continue;
                }
                $result[$key] = $value;
            }
        }
        return $result;
    }

    /**
     * метод получения данных для просмотра
     * @param $id
     * @return array
     */
    public function getDataAll()
    {
        $result = [];
        $objects = $this->getAll();
        foreach ($objects as $object) {
            foreach ($object as $key => $value) {
                if (gettype($key) == 'integer') {
                    continue;
                }
                $result[$key] = $value;
            }
        }
        return $result;
    }

    public function insert(Entity $entity)
    {
        $columns = [];
        $params = [];

        foreach ($entity as $key => $value) {
            if ($key == 'id') {
                continue;
            }
            $columns[] = $key;
            $params[":{$key}"] = $value;
        }

        $params[":date_item"] = date('Y/m/d', time());
        $columnsString = implode(', ', $columns);
        $placeholders = implode(', ', array_keys($params));
        $tableName = $this->getTableName();
        $sql = "INSERT INTO {$tableName} ({$columnsString})
          VALUES ({$placeholders})";
        $this->bd->execute($sql, $params);
        $this->id = $this->bd->lastInsertId();
    }

    public function update($entity)
    {
        $keyValueArray = [];
        $params = [];

        foreach ($entity as $key => $value) {
            $params[":{$key}"] = $value;
            $keyValueArray[] = "{$key}=:{$key}";
        }

        $keyValuesString = implode(', ', $keyValueArray);

        $tableName = $this->getTableName();
        $sql = "UPDATE {$tableName} SET {$keyValuesString} WHERE id={$entity->id}";
        $this->bd->execute($sql, $params);
    }

    public function delete($entity)
    {
        $id = $entity->id;
        $tableName = $this->getTableName();
        $sql = "DELETE FROM {$tableName} WHERE id = :id ";
        $this->bd->execute($sql, [':id' => $id]);
    }
}

<?php

namespace App\models\entities;

use App\services\BD;

/**
 * Class Entity
 * @package App\models\entites
 *
 * @property int id
 */
class Entity
{
    public function getProperties()
    {
//        $tableProperties = $this->getTableProperties();
        $properties = [];
        $specialProperties = $this->getProtectedProperties() ?: [];
        foreach ($this as $key => $value) {
            if (in_array($key, $specialProperties)) {
                continue;
            }
            $properties[] = $key;
        }
        return $properties;
    }

    public function getTableProperties()
    {
        $result = [];
        $arr = BD::getInstance()->getColumns($this->getTableName());
        foreach ($arr as $item) {
            $result[] = $item['COLUMN_NAME'];
        }
        return $result;
    }
}

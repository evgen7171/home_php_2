<?php

namespace App\models\entities;
/**
 * Class Good
 * @package App\models
 * @method static getOne($id)
 */
class Good extends Entity
{
    public $id;
    public $price;
    public $name_item;
    public $info;
    public $date_item;

    protected function getProtectedProperties()
    {
        $properties = [
            'date_item'
        ];
        $properties[] = 'bd';
        return $properties;
    }

    protected static function getTableName()
    {
        return 'goods';
    }

}
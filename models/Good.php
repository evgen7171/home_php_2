<?php
namespace App\models;

use App\traits\TProperties;

class Good extends Model
{
    protected $id;
    protected $price;
    protected $name;
    protected $info;

    protected function getTableName()
    {
        return 'products';
    }

    use TProperties;
}
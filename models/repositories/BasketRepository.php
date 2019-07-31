<?php
/**
 * Created by PhpStorm.
 * User: Админ
 * Date: 31.07.2019
 * Time: 23:41
 */

namespace App\models\repositories;


class BasketRepository
{
    protected function getTableName()
    {
        return 'baskets';
    }

    protected function getEntityName()
    {
        return Baskets::class;
    }
}
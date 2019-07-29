<?php
namespace App\models\repositories;
use App\models\entities\Good;

/**
 * Class GoodRepository
 * @package App\models\repository
 */
class GoodRepository extends Repository
{
    protected function getTableName()
    {
        return 'goods';
    }

    /**
     * метод, который возвращает имя сущности
     * @return mixed
     */
    protected function getEntityName()
    {
        return Good::class;
    }
}
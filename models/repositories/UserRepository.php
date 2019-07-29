<?php
namespace App\models\repositories;

use App\models\entities\User;

/**
 * Class UserRepository
 * @package App\models\repository
 *
 * @method User getOne($id)
 */
class UserRepository extends Repository
{
    public function getTableName()
    {
        return 'users';
    }

    /**
     * метод, который возвращает имя сущности
     * @return mixed
     */
    protected function getEntityName()
    {
        return User::class;
    }
}

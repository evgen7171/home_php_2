<?php
namespace App\models\entities;

/**
 * Class User
 * @package App\models
 * @method delete()
 */
class User extends Entity
{
    public $id;
    public $fio;
    public $login;
    public $password;
    public $date_item;
    public $is_admin;

    protected function getProtectedProperties()
    {
        $properties = [
            'is_admin',
            'date_item'
        ];
        $properties[] = 'bd';
        return $properties;
    }

    protected static function getTableName()
    {
        return 'users';
    }

}

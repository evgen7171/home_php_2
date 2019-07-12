<?php
namespace App\models;
use \App\services\IBD2;

class User extends Model implements IBD2
{
    use CalcRows;

    public $id;
    public $name;
    public $login;
    public $password;

    protected function getTableName()
    {
        return 'users';
    }

}

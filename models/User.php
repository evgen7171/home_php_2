<?php

namespace App\models;

use App\traits\TProperties;

class User extends Model
{
    private $id;
    private $name;
    private $login;
    private $password;

    protected function getTableName()
    {
        return 'users';
    }

    use TProperties;
}

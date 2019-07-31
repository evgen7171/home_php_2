<?php
namespace App\models\entities;

/**
 * Class User
 * @package App\models\entities
 */
class User extends Entity
{
    public $id;
    public $fio;
    public $login;
    public $password;
    public $date;
    public $is_admin;

    public function getFullName()
    {
        return $this->login . '| ' . $this->fio;
    }
}

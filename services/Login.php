<?php
/**
 * Created by PhpStorm.
 * User: Админ
 * Date: 04.08.2019
 * Time: 19:09
 */

namespace App\services;


class Login
{
    public $string;

    public function __construct($string)
    {
        $this->string = $string;
    }

    public function validate()
    {
        $pattern = '/[A-Za-z]+/';
        preg_match_all($pattern, $this->string, $match);
        $str = implode('', $match[0]);
        return $this->string == $str;
    }
}
<?php

namespace App\tests;

use App\services\Login;
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    public function testValidate()
    {
        $login = new Login('login');
        $this->assertTrue($login->validate());
    }
}
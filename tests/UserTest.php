<?php

namespace App\tests;

use App\main\App;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserAddition()
    {
        $product = [
            'id' => 123,
            'fio' => 'Иван',
            'login' => 'ivan',
            'password' => 123
        ];
        $users = App::call()->userRepository->getAll();
        echo $users;
//        $products = $basket->getProducts();
//        $basket->addProduct($product);
//        $products_new = $basket->getProducts();
//        $products_added = array_merge($products, $product);
//        $this->assertEquals($products_new, $products_added);
    }
}
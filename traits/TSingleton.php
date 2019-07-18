<?php
namespace App\traits;

/**
 * Trait TSingleton - доавление данного кода позволяет сделать класс единственным,
 * и становится невозможно его клонировать, наследовать, сериализовать,
 * т.е. позволяет сделать класс уникальным.
 * @package App\traits
 */
trait TSingleton
{
    private static $items;

    protected function __construct(){}
    protected function __clone(){}
    protected function __wakeup(){}

    /**
     * Создание одного единственного экземпляра класса
     * @return mixed
     */
    public static function getInstance()
    {
        if (empty(static::$items)) {
            static::$items = new static();
        }
        return static::$items;
    }
}

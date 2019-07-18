<?php

namespace App\services;

/**
 * Interface IBD - интерфейс для работы класса BD
 * @package App\services
 */
interface IBD
{
    public function find(string $sql, array $params = []);

    public function findAll(string $sql, array $params = []);
}
<?php
namespace App\services;

interface IBD2
{
    public function calc(array $rows): int;
}
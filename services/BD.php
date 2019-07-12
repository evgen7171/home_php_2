<?php
namespace App\services;
use \App\models\CalcRows as CalcRows;

class BD implements IBD, IBD2
{
    use CalcRows;

    public function find(string $sql)
    {
        echo $sql;
    }

    public function findAll(string $sql)
    {
        return $sql;
    }

    /**
     * @param array $rows
     * @return int
     */
    public function calc(array $rows): int
    {
        // TODO: Implement calc() method.
    }
}
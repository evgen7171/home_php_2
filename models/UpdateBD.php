<?php

namespace App\models;

class UpdateBD extends Model
{
    public function __construct($bd, $obj)
    {
        parent::__construct($bd);
        $this->obj = $obj;
        $this->update();
    }

    /**
     * Данный метод должен записать/обновить/ данные в базу данных
     */
    public function update()
    {
        //TODO: update() method.
    }

    /**
     * Данный метод должен вернуть название таблицы
     * @return string
     */
    protected function getTableName()
    {
        // TODO: Implement getTableName() method.
    }
}
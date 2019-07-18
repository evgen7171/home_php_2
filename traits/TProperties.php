<?php


namespace App\traits;


trait TProperties
{
    /**
     * Метод получения всех свойств объекта
     */
    public function getProperties()
    {
        $properties = [];
        foreach ($this as $key => $value) {
            if ($key !== 'bd') {
                $properties[] = $key;
            }
        }
        return $properties;
    }

    /**
     * Получить значение опеределенного свойства (из таблицы базы данных)
     * @param $id
     * @param $prop
     * @return mixed
     */
    public function getProperty($id, $prop)
    {
        return $this->getValueProperty($id, $prop);
    }

    /**
     * Установить значение опеределенного свойства (из таблицы базы данных)
     * @param $id
     * @param $prop
     * @param $value
     * @return mixed
     */
    public function setProperty($id, $prop, $value)
    {
        return $this->setValueProperty($id, $prop, $value);
    }
}
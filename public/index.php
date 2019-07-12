<?php

include $_SERVER['DOCUMENT_ROOT'] .
    '/../services/Autoload.php';

spl_autoload_register(
    [new Autoload(),
        'loadClass']
);

$namespaces = [
    'models', 'services'
];
foreach ($namespaces as $namespace) {
    $str = scandir($_SERVER['DOCUMENT_ROOT'] .
        "/../" . $namespace);
    $classNames = [];
    foreach ($str as $item) {
        if ($item !== "." && $item !== "..") {
            $className = mb_substr($item, 0, mb_strlen($item) - 4);
            array_push($classNames, $className);
        }
    }
}

/*
$fileN = $_SERVER['DOCUMENT_ROOT'] .
    '/../models';
$ss = scandir($fileN);
foreach ($ss as $s) {
    use '\\models\\'.$s;
}
*/

use App\models\User;
use App\models\Good;
use App\services\BD;

$user = new User(new BD());

$user->getOne(12);
echo '<br>';
$good = (new Good(new BD()))->getAll();

var_dump($good);
echo '<br>';
var_dump($user->calc([1, 15, 456, 456]));

/*

1. Создать несколько классов - наследников класса Model,
используемые для сохранения данных из базы данных.
2. Добавить для каждого класса неймспейс,
соответствующий его месту в деректории.
Каждый неймспейс должен начинаться с App
3. Переписать автозагрусчик с учетом созданных пространств имен.


*/
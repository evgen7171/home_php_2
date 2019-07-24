<?php

use  \App\models\User;
use  \App\services\BD;

include $_SERVER['DOCUMENT_ROOT'] .
    '/../services/Autoload.php';

spl_autoload_register(
    [new Autoload(),
        'loadClass']
);

include $_SERVER['DOCUMENT_ROOT'].'/../config/const.php';

$controllerName = $_GET['controller'] ?: 'user';
$actionName = $_GET['action'];
$tableName = $_GET['table'];
$id = $_GET['id'];
$unitData = $_POST;

$controllerClass = 'App\\controllers\\' .
    ucfirst($controllerName) . 'Controller';

if (class_exists($controllerClass)) {
    $controller = new $controllerClass();
    $controller->run($actionName, $tableName, $id, $unitData);

}

// TODO сделать чтобы меню всех позиций отображалось при наведении
// TODO * добавить класс Cart, добавить CartController
// TODO js - показывает passqord для User только при прохождении простой проверки
// TODO js - показывает info для Good не сразу а при нажатиина кнопку
// TODO привести в красивый вид (красивое меню, красивый контент)

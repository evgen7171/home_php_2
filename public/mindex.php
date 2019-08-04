<?php

include $_SERVER['DOCUMENT_ROOT'].'/../services/Login.php';
use App\services\Login;

$login = new Login('logi32423пвлаоп');
var_dump($login->validate());
<?php
require_once '../app/Config.php';
require_once '../app/AutoLoad.php';

define('DEFAULT_CONTROLLER', 'HomeController');
define('DEFAULT_ACTION', 'indexAction');
define('DEFAULT_ROUTE', 'home');

define ("DB_CONN_PARAMS", serialize (array(
        'host' => '',
        'username' => '',
        'password' => '',
        'db' => ''
    )));

define('EMAIL_PARAMS', serialize(array(
        'host' => '',
        'from' => '',
        'username' => '',
        'password' => '',
    )));

$app = new App();
$app->addRoute('home', 'Home', false);
$app->addRoute('register', 'Register', false);
$app->addRoute('login', 'Login', false);
$app->addRoute('detail', 'Detail', true);
$app->addRoute('email', 'Email', false);
$app->addRoute('logout', 'Logout', false);
$app->run();
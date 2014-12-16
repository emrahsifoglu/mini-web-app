<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('ROOT', str_replace("/web","/", dirname($_SERVER['PHP_SELF'])));
define('WEB', ROOT.'web/');
define('SRC', ROOT.'src/');
define('APP', ROOT.'app/');
define('RESOURCES', WEB.'resources/');
define('STYLES', RESOURCES.'public/css/');
define('SCRIPTS', RESOURCES.'public/js/');
define('IMAGES', RESOURCES.'public/images/');
define('BOWER', RESOURCES.'public/bower/');

//define('BASE_PATH', dirname(realpath(__FILE__)));
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
define('SRC_PATH', ROOT_PATH.SRC);
define('WEB_PATH', ROOT_PATH.WEB);
define('APP_PATH', ROOT_PATH.APP);
define('CORE_PATH', APP_PATH.'core/');

define('CONTROLLER_PATH',SRC_PATH.'mvc/Controllers/');
define('MODEL_PATH', SRC_PATH.'mvc/Models/');
define('VIEW_PATH', SRC_PATH.'mvc/Views/');
define('LAYOUT', WEB_PATH.'resources/views/layout.php');

class Config {

}
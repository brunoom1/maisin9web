<?php 

define('PATH_ROOT', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);

require PATH_ROOT . DS . 'vendor' . DS .'autoload.php'; 

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

define("FACEBOOK_APP_ID", getenv('FACEBOOK_APP_ID')); 
define("FACEBOOK_SECRED_KEY", getenv("FACEBOOK_SECRED_KEY"));


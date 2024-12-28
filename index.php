<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('BASE_PATH', __DIR__);
define('URL_PREFIX', str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']));
define('BASE_URL', isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https://' : 'http://'. $_SERVER['HTTP_HOST'] . URL_PREFIX);

require './vendor/autoload.php';
require_once './src/Helpers/view.php';

$router = require './src/Routes/index.php';

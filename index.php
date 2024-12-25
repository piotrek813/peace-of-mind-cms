<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('BASE_PATH', __DIR__);

require './vendor/autoload.php';
require_once './src/Helpers/view.php';

$router = require './src/Routes/index.php';

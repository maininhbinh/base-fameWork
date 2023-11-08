<?php
require_once './config/App.php';
require_once './vendor/autoload/my_autoload.php';
$config = require('config/config.php');
App::setConfig($config);

new my_autoload();

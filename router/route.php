<?php

use Vendor\route\RouteCollector;

$router = new RouteCollector();
$url = !isset($_GET['url']) ? "/" : $_GET['url'];

$router->get('/', [App\Controllers\useController::class, 'index']);

$router->get('/home', function () {
    echo 'đây là trang home';
});

$router->get('*', function () {
    echo '404';
});


$router->dispatch($_SERVER['REQUEST_METHOD'], $url);

$router->run();

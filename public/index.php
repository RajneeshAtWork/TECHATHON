<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\App;

App::boot();

$router = require dirname(__DIR__) . '/routes/web.php';

$response = $router->dispatch(
    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI']
);

if ($response !== null) {
    echo $response;
}
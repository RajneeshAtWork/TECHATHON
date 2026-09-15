<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\App;
use App\Core\Request;
use App\Core\Response;

App::boot();

$request = new Request();
$response = new Response();

$router = require dirname(__DIR__) . '/routes/web.php';

$result = $router->dispatch(
    $request->method(),
    $request->uri()
);

if ($result !== null) {
    $response->html($result);
}
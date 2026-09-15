<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(
        string $path,
        array|callable $handler,
        array $middleware = []
    ): void {
        $this->addRoute('GET', $path, $handler, $middleware);
    }

    public function post(
        string $path,
        array|callable $handler,
        array $middleware = []
    ): void {
        $this->addRoute('POST', $path, $handler, $middleware);
    }

    private function addRoute(
        string $method,
        string $path,
        array|callable $handler,
        array $middleware = []
    ): void {
        $this->routes[$method][$path] = [
            'handler' => $handler,
            'middleware' => $middleware,
        ];
    }

    public function dispatch(
        string $method,
        string $uri
    ): mixed {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';

        /*
         * The application is running from:
         * /TECHATHON/public/
         */
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';

        $basePath = rtrim(
            str_replace(
                '/index.php',
                '',
                dirname($scriptName)
            ),
            '/'
        );

        if (
            $basePath !== '' &&
            str_starts_with($path, $basePath)
        ) {
            $path = substr(
                $path,
                strlen($basePath)
            );
        }

        $path = '/' . ltrim($path, '/');
        $path = rtrim($path, '/') ?: '/';

        if (!isset($this->routes[$method][$path])) {
            http_response_code(404);

            return '404 - Page Not Found';
        }

        $route = $this->routes[$method][$path];

        /*
         * Run middleware before the controller.
         */
        foreach ($route['middleware'] as $middleware) {
            if (is_array($middleware)) {
                $middlewareClass = $middleware[0];
                $middlewareArguments = $middleware[1] ?? [];

                $middlewareClass::handle(...$middlewareArguments);
            } else {
                $middleware::handle();
            }
        }

        $handler = $route['handler'];

        if (is_callable($handler)) {
            return call_user_func($handler);
        }

        if (is_array($handler)) {
            [$controller, $action] = $handler;

            $controllerInstance = new $controller();

            return $controllerInstance->$action();
        }

        http_response_code(500);

        return '500 - Invalid Route Handler';
    }
}
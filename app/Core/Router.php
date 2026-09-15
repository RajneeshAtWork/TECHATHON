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
        $this->addRoute(
            'GET',
            $path,
            $handler,
            $middleware
        );
    }

    public function post(
        string $path,
        array|callable $handler,
        array $middleware = []
    ): void {
        $this->addRoute(
            'POST',
            $path,
            $handler,
            $middleware
        );
    }

    private function addRoute(
        string $method,
        string $path,
        array|callable $handler,
        array $middleware = []
    ): void {
        $this->routes[$method][] = [
            'path' => $path,
            'handler' => $handler,
            'middleware' => $middleware,
        ];
    }

    public function dispatch(
        string $method,
        string $uri
    ): mixed {
        $path = parse_url(
            $uri,
            PHP_URL_PATH
        ) ?: '/';

        $scriptName =
            $_SERVER['SCRIPT_NAME'] ?? '';

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
            str_starts_with(
                $path,
                $basePath
            )
        ) {
            $path = substr(
                $path,
                strlen($basePath)
            );
        }

        $path = '/' . ltrim(
            $path,
            '/'
        );

        $path = rtrim(
            $path,
            '/'
        ) ?: '/';

        $routes =
            $this->routes[$method] ?? [];

        foreach ($routes as $route) {

            /*
            |--------------------------------------------------------------------------
            | Convert route parameters into regex patterns
            |--------------------------------------------------------------------------
            |
            | Example:
            |
            | /organizer/hackathons/{id}/edit
            |
            | becomes:
            |
            | #^/organizer/hackathons/([^/]+)/edit/?$#
            |
            */
            $pattern = preg_replace(
                '/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/',
                '([^/]+)',
                $route['path']
            );

            $pattern = '#^' .
                rtrim($pattern, '/') .
                '/?$#';

            if (!preg_match(
                $pattern,
                $path,
                $matches
            )) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Remove the full matched URL
            |--------------------------------------------------------------------------
            */
            array_shift($matches);

            /*
            |--------------------------------------------------------------------------
            | Convert numeric route parameters to integers
            |--------------------------------------------------------------------------
            |
            | preg_match() always returns captured values as strings.
            |
            | Example:
            |
            | "15" -> 15
            |
            | This allows controller methods such as:
            |
            | edit(int $id)
            | update(int $id)
            | submitForApproval(int $id)
            |
            */
            $matches = array_map(
                static function ($value) {
                    if (
                        is_string($value) &&
                        preg_match('/^\d+$/', $value)
                    ) {
                        return (int) $value;
                    }

                    return $value;
                },
                $matches
            );

            /*
            |--------------------------------------------------------------------------
            | Execute Middleware
            |--------------------------------------------------------------------------
            */
            foreach (
                $route['middleware']
                as $middleware
            ) {
                if (is_array($middleware)) {

                    $middlewareClass =
                        $middleware[0];

                    $middlewareArguments =
                        $middleware[1] ?? [];

                    $middlewareClass::handle(
                        ...$middlewareArguments
                    );

                } else {

                    $middleware::handle();

                }
            }

            /*
            |--------------------------------------------------------------------------
            | Execute Route Handler
            |--------------------------------------------------------------------------
            */
            $handler =
                $route['handler'];

            if (is_callable($handler)) {
                return call_user_func(
                    $handler,
                    ...$matches
                );
            }

            if (is_array($handler)) {

                [
                    $controller,
                    $action
                ] = $handler;

                $controllerInstance =
                    new $controller();

                return $controllerInstance->$action(
                    ...$matches
                );
            }

            http_response_code(500);

            return '500 - Invalid Route Handler';
        }

        /*
        |--------------------------------------------------------------------------
        | No Route Matched
        |--------------------------------------------------------------------------
        */
        http_response_code(404);

        return '404 - Page Not Found';
    }
}
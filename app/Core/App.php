<?php

namespace App\Core;

use Dotenv\Dotenv;

class App
{
    private static array $config = [];


    public static function boot(): void
    {
        date_default_timezone_set('Asia/Kolkata');

        self::loadEnvironment();
        self::loadConfiguration();

        Session::start();
    }


    private static function loadEnvironment(): void
    {
        $rootPath = dirname(__DIR__, 2);

        $dotenv = Dotenv::createImmutable($rootPath);

        $dotenv->safeLoad();
    }


    private static function loadConfiguration(): void
    {
        self::$config = [
            'app' =>
                require dirname(__DIR__, 2)
                . '/config/app.php',

            'database' =>
                require dirname(__DIR__, 2)
                . '/config/database.php',
        ];
    }


    public static function config(
        string $key,
        mixed $default = null
    ): mixed {
        $segments = explode('.', $key);

        $value = self::$config;

        foreach ($segments as $segment) {

            if (
                !is_array($value) ||
                !array_key_exists($segment, $value)
            ) {
                return $default;
            }

            $value = $value[$segment];
        }

        return $value;
    }
}
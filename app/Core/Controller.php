<?php

namespace App\Core;

class Controller
{
    protected function view(string $view, array $data = []): string
    {
        $viewPath = dirname(__DIR__) . '/Views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            throw new \RuntimeException(
                "View not found: {$view}"
            );
        }

        extract($data);

        ob_start();

        require $viewPath;

        return ob_get_clean();
    }
}
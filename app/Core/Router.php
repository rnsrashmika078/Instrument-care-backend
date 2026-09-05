<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, array $action): void
    {
        $this->routes['GET'][$path] = $action;
    }

    public function post(string $path, array $action): void
    {
        $this->routes['POST'][$path] = $action;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];

        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Remove project folder from URI
        $uri = str_replace(
            '/Instrument-care-backend-refine',
            '',
            $uri
        );
        // var_dump($_SERVER['REQUEST_METHOD']);
        // var_dump($_SERVER['REQUEST_URI']);
        // var_dump($uri);
        // exit;
        $route = $this->routes[$method][$uri] ?? null;

        if ($route === null) {
            Response::json([
                'message' => 'Route not found'
            ], 404);
        }

        [$controller, $action] = $route;

        $controller = new $controller();

        $controller->$action();
    }
}

<?php

declare(strict_types=1);

namespace App\Routing;

class RouteCollection
{
    private array $routes = [];

    public function add(string $method, string $path, string $controller, string $action, bool $authenticated = false): RouteCollection
    {
        $this->checkMethod($method);

        $this->routes[$method][$path] = new Route($path, $controller, $action, $authenticated);

        return $this;
    }

    private function checkMethod(string $method): void
    {
        if (empty($this->routes[$method])) {
            $this->routes[$method] = [];
        }
    }

    public function filter(string $method): array
    {
        $this->checkMethod($method);

        return $this->routes[$method];
    }

    public function all(): array
    {
        return $this->routes;
    }
}
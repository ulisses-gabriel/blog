<?php

declare(strict_types=1);

namespace App;

use App\DependencyInjection\DependencyResolver;
use App\Routing\Route;
use App\Routing\Router;

class App
{
    private Router $router;
    private bool $runningInConsole;

    public function __construct(bool $runningInConsole = false)
    {
        $this->runningInConsole = $runningInConsole;
        $path = $_SERVER['PATH_INFO'] ?? '/';
        $requestedMethod = $_SERVER['REQUEST_METHOD'] ?? Router::METHOD_GET;
        $this->router = new Router($path, $requestedMethod);

        session_start();
    }

    public function isRunningInConsole(): bool
    {
        return $this->runningInConsole;
    }

    public function run(): void
    {
        $route = $this->router->run();

        if (!$route['callback']) {
            throw new \Exception('Route not found');
        }

        $resolver = new DependencyResolver();
        /** @var Route $callback */
        $callback = $route['callback'];
        $controllerClass = $resolver->getClass($callback->getController());
        $action = $callback->getAction();

        $controllerClass->$action($route['params']);
    }

    public function get(string $path, string $controller, string $action, bool $authenticated = false): App
    {
        $this->router->get($path ?: '/', $controller, $action, $authenticated);

        return $this;
    }

    public function post(string $path, string $controller, string $action, bool $authenticated = false): App
    {
        $this->router->post($path ?: '/', $controller, $action, $authenticated);

        return $this;
    }
}

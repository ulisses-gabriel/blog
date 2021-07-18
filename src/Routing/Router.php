<?php

declare(strict_types=1);

namespace App\Routing;

class Router
{
    private RouteCollection $routeCollection;
    private string $path;
    private string $method;

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';

    public function __construct(string $path, string $method)
    {
        $this->routeCollection = new RouteCollection();
        $this->path = $path;
        $this->method = $method;
    }

    public function get(string $path, string $controller, string $action, bool $authenticated = false): Router
    {
        $this->routeCollection->add(self::METHOD_GET, $path, $controller, $action, $authenticated);

        return $this;
    }

    public function post(string $path, string $contrller, string $action, bool $authenticated = false): Router
    {
        $this->routeCollection->add(self::METHOD_POST, $path, $contrller, $action, $authenticated);

        return $this;
    }

    public function run(): array
    {
        $methodRoutes = $this->routeCollection->filter($this->method);
        $result = [];
        $callback = null;

        foreach ($methodRoutes as $path => $methodRoute) {
            /** @var Route $methodRoute */
            $result = $this->checkUrl($path, $this->path);

            if ($result['result']) {
                $callback = $methodRoute;

                break;
            }
        }

        if ($callback && $callback->requiresAuthenticated() && !isLogged()) {
            header('Location: /login');
        }

        return [
            'params' => $result['params'],
            'callback' => $callback,
        ];
    }

    private function checkUrl(string $path, string $subject): array
    {
        preg_match_all('/\{([^\}]*)\}/', $path, $variables);

        $regex = str_replace('/', '\/', $path);

        foreach ($variables[1] as $key => $variable) {
            $as = explode(':', $variable);
            $replacement = $as[1] ?? '([a-zA-Z0-9\-\_\ ]+)';
            $regex = str_replace($variables[$key], $replacement, $regex);
        }

        $regex = preg_replace('/{([a-zA-Z]+)}/', '([a-zA-Z0-9+])', $regex);
        $result = preg_match('/^' . $regex . '$/', $subject, $params);

        return compact('result', 'params');
    }

    public function isGet(): bool
    {
        return strtoupper($this->method) === self::METHOD_GET;
    }

    public function isPost(): bool
    {
        return strtoupper($this->method) === self::METHOD_POST;
    }
}
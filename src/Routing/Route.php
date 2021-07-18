<?php

declare(strict_types=1);

namespace App\Routing;

class Route
{
    private string $path;
    private string $controller;
    private string $action;
    private bool $authenticated;

    public function __construct(string $path, string $controller, string $action, bool $authenticated = false)
    {
        $this->path = $path;
        $this->controller = $controller;
        $this->action = $action;
        $this->authenticated = $authenticated;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function requiresAuthenticated(): bool
    {
        return $this->authenticated;
    }
}

<?php

declare(strict_types=1);

namespace App\Controllers;

abstract class Controller
{
    protected \stdClass $view;

    public function __construct()
    {
        $this->view = new \stdClass();
    }

    protected function render(string $template): void
    {
        $layoutFile = '../src/Views/layout.phtml';
        $this->view->template = $template;

        if (file_exists($layoutFile)) {
            include_once $layoutFile;
        } else {
            $this->content();
        }
    }

    protected function content(): void
    {
        if (empty($this->view->template)) {
            return;
        }

        $viewFile = "../src/Views/{$this->view->template}.phtml";

        if (!file_exists($viewFile)) {
            throw new \InvalidArgumentException(sprintf('View file [%s] does not exists.', $viewFile));
        }

        include_once $viewFile;
    }

    protected function redirectTo(string $to): void
    {
        header("Location: {$to}");
    }

    protected function redirectBack(): void
    {
        $this->redirectTo($_SERVER['HTTP_REFERER'] ?? '/');
    }
}
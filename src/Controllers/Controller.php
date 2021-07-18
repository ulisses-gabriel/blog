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
}
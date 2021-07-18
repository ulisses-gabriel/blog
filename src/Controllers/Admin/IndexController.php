<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
        $this->render('admin/index');
    }
}
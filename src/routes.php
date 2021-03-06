<?php

/** @var \App\App $app */

use App\Controllers as Controllers;
use App\Controllers\Admin as AdminControllers;

$app->get('', Controllers\HomeController::class, 'index')
    ->get('/login', Controllers\LoginController::class, 'index')
    ->post('/login', Controllers\LoginController::class, 'login')
    ->get('/logout', Controllers\LoginController::class, 'logout', true)
    ->get('/posts/{post}', Controllers\PostsController::class, 'show')
    ->get('/admin', AdminControllers\IndexController::class, 'index', true)
    ->get('/admin/posts', AdminControllers\PostsController::class, 'index', true)
    ->get('/admin/posts/new', AdminControllers\PostsController::class, 'create', true)
    ->post('/admin/posts/new', AdminControllers\PostsController::class, 'store', true)
    ->get('/admin/posts/{post}', AdminControllers\PostsController::class, 'edit', true)
    ->post('/admin/posts/{post}', AdminControllers\PostsController::class, 'update', true)
    ->get('/admin/posts/{post}/delete', AdminControllers\PostsController::class, 'delete', true);
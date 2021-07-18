<?php

/** @var \App\App $app */

use App\Controllers as Controllers;

$app->get('', Controllers\HomeController::class, 'index')
    ->get('/login', Controllers\LoginController::class, 'index')
    ->post('/login', Controllers\LoginController::class, 'login')
    ->get('/logout', Controllers\LoginController::class, 'logout');
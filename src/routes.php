<?php

/** @var \App\App $app */

use App\Controllers as Controllers;

$app->get('', Controllers\HomeController::class, 'index')
    ->get('/{test}', Controllers\HomeController::class, 'index');
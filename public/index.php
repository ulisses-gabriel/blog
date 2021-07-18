<?php

require __DIR__ . '/../vendor/autoload.php';

$app = new \App\App();

require __DIR__ . '/../src/routes.php';

try {
    $app->run();
} catch (Throwable $exception) {
    dd($exception);
}
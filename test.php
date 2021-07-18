<?php

require __DIR__ . '/vendor/autoload.php';

function dd()
{
    foreach (func_get_args() as $arg) {
        dump($arg);
    }

    die;
}

$user = new \App\Models\User();

dd($user);
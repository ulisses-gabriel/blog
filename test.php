<?php

require __DIR__ . '/vendor/autoload.php';

function dd()
{
    foreach (func_get_args() as $arg) {
        dump($arg);
    }

    die;
}

$user = (new \App\Models\User())->first();

//$user->name = 'test';
//$user->email = 'test@email.com';
//$user->password = '123';
//
//$user = $user->save();

$user->name = 'Test changed';

$user->update();

dd($user);
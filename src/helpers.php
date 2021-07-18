<?php

function config(string $key)
{
    $configKeys = explode('.', $key);
    $configs = require __DIR__ . '/config.php';
    $config = null;

    foreach ($configKeys as $configKey) {
        if (!$config) {
            $config = $configs[$configKey] ?? null;
        } else {
            $config = $config[$configKey] ?? null;
        }

        if ($config === null) {
            break;
        }
    }

    return $config;
}

function pluralize(string $word)
{
    $lastLetter = strtolower($word[strlen($word)-1]);

    switch ($lastLetter) {
        case 'y':
            return substr($word, 0, -1) . 'ies';
        case 's':
            return $word . 'es';
        default:
            return $word . 's';
    }
}
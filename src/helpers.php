<?php

use App\Models\User;

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

function pluralize(string $word): string
{
    $lastLetter = strtolower($word[strlen($word) - 1]);

    switch ($lastLetter) {
        case 'y':
            return substr($word, 0, -1) . 'ies';
        case 's':
            return $word . 'es';
        default:
            return $word . 's';
    }
}

function isLogged(): bool
{
    return !empty($_SESSION['loggedUser']);
}

function login(User $user): void
{
    $user->setPdoAdapter(null);

    $_SESSION['loggedUser'] = $user;
}

function logout(): void
{
    unset($_SESSION['loggedUser']);
}

function loggedUser(): User
{
    return $_SESSION['loggedUser'];
}

function hashPassword(string $password): string
{
    return password_hash($password, PASSWORD_BCRYPT);
}

function passwordMatches(string $password, string $hashedPassword): bool
{
    return password_verify($password, $hashedPassword);
}

function error(string $type, string $message): void
{
    if (empty($_SESSION['errors'])) {
        $_SESSION['errors'] = [];
    }

    $_SESSION['errors'][$type] = $message;
}

function errors(string $type)
{
    return $_SESSION['errors'][$type] ?? null;
}

function clearErrors(): void
{
    unset($_SESSION['errors']);
}

function clearMessage(): void
{
    unset($_SESSION['message']);
}

function slugify(string $text, string $divider = '-'): string
{
    // replace non letter or digits by divider
    $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);
    // trim
    $text = trim($text, $divider);
    // remove duplicate divider
    $text = preg_replace('~-+~', $divider, $text);
    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}
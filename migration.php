<?php

use App\Database\Connection\Connection;
use App\Models\User;

require __DIR__ . '/vendor/autoload.php';

$driver = config('database.driver');
$connection = Connection::getConnection(false);

$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$database = config('database.connections')[$driver]['dbname'];
$tablesSQL = [
    Connection::MYSQL_CONNECTION => [
        'users' => getCreateUsersTableForMySQL(),
        'posts' => getCreatePostsTableForMySQL(),
    ],
    Connection::SQLITE_CONNECTION => [
        'users' => getCreateUsersTableForSQLite(),
        'posts' => getCreatePostsTableForSQLite(),
    ],
];

if ($driver === Connection::MYSQL_CONNECTION) {
    $createDB = "CREATE DATABASE IF NOT EXISTS {$database}";

    echo "Creating database {$database}..." . PHP_EOL;

    if ($connection->prepare($createDB)->execute()) {
        $connection = Connection::getConnection();
    } else {
        dd('Something went wrong');
    }

    echo "Database {$database} created." . PHP_EOL;
}

$createUsersTable = $tablesSQL[$driver]['users'];
$createPostsTable = $tablesSQL[$driver]['posts'];

echo "Creating users table..." . PHP_EOL;

$connection->exec($createUsersTable);

echo "Users table created." . PHP_EOL;
echo "Creating posts table..." . PHP_EOL;

$connection->exec($createPostsTable);

echo "Posts table created." . PHP_EOL;

/*
 * Lets create a default user now
 * */

echo "Creating default user..." . PHP_EOL;

$userPassword = config('migration.default_user.password');
$userEmail = config('migration.default_user.email');
$userName = config('migration.default_user.name');

if ($user = (new User())->findOneBy([['email', $userEmail]])) {
    echo "Default user already exists." . PHP_EOL;

    return;
}

$user = new User();

$user->name = $userName;
$user->email = $userEmail;
$user->password = password_hash($userPassword, PASSWORD_BCRYPT);

$user->save();

echo "User created." . PHP_EOL;
echo "You can log into the admin using the email [$user->email] and password [$userPassword]" . PHP_EOL;;

function getCreateUsersTableForMySQL(): string
{
    return "CREATE TABLE IF NOT EXISTS users (
        `id` int unsigned NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `email` varchar(255) NOT NULL,
        `password` varchar(255) NOT NULL,
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `users_email` (`email`)
    )";
}

function getCreatePostsTableForMySQL(): string
{
    return "CREATE TABLE IF NOT EXISTS posts (
        `id` int unsigned NOT NULL AUTO_INCREMENT,
        `author_id` int unsigned NOT NULL,
        `title` varchar(255) NOT NULL,
        `slug` varchar(255) NOT NULL,
        `content` text NOT NULL,
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `posts_slug` (`slug`),
        KEY `posts_author` (`author_id`),
        CONSTRAINT `posts_author` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`)
    )";
}

function getCreateUsersTableForSQLite(): string
{
    return "CREATE TABLE IF NOT EXISTS `users` (
            `id` integer not null primary key autoincrement,
            `name` text NOT NULL,
            `email` text NOT NULL,
            `password` text NOT NULL,
            `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP
        )
    ";
}

function getCreatePostsTableForSQLite(): string
{
    return "CREATE TABLE IF NOT EXISTS `posts` (
        `id` integer NOT NULL primary key autoincrement,
        `author_id` integer NOT NULL,
        `title` text NOT NULL,
        `slug` text NOT NULL unique,
        `content` text NOT NULL,
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (`author_id`) REFERENCES `users`(`id`)
    )";
}

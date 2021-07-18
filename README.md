# Welcome to a simple blog from scratch

## Features
- Auto database setup
- You can log in the blog
- You can view all posts
- You can create a post
- You can edit a post
- You can delete a post

## Installation

The blog requires PHP 7.4+ to run.

Install the dependencies.

```
cd blog
composer install
```

## Database setup

Now you need to setup your database, but dont worry I gotcha!

Edit the configs inside `src/config.php` and enter your database credentials. 
There you can choose to use MySQL or SQLite.

```
'database' => [
    'driver' => Connection::MYSQL_CONNECTION,
    'connections' => [
        Connection::MYSQL_CONNECTION => [
            'host' => '192.168.10.10',
            'dbname' => 'blog',
            'user' => 'homestead',
            'password' => 'secret',
        ],
        Connection::SQLITE_CONNECTION => [
            'dbname' => 'blog',
        ],
    ],
],
```

If you want a default user with different data, you can edit them in the same config file, under the migration section:

```
'migration' => [
    'default_user' => [
        'name' => 'User',
        'email' => 'user@email.com',
        'password' => 'password',
    ]
]
```

Then run the migration:

```
php migration.php
```

If everything was ok, you should see something like this:

```
$ php migration.php
Creating database blog...
Database blog created.
Creating users table...
Users table created.
Creating posts table...
Posts table created.
Creating default user...
User created.
You can log into the admin using the email [user@email.com] and password [password]
```

## Start the server

To start the server, there is a file `start-server.sh`
```
sh start-server.sh
```

And you are ready to go. You can access the blog through the url `http://localhost:9000` on your browser.
<?php

return [
    'driver' => getenv('DB_CONNECTION') ?: 'sqlite',
    'host' => getenv('DB_HOST') ?: 'localhost',
    'port' => getenv('DB_PORT') ?: '3306',
    'database' => getenv('DB_DATABASE') ?: __DIR__ . '/../database/banco.sqlite',
    'username' => getenv('DB_USERNAME') ?: null,
    'password' => getenv('DB_PASSWORD') ?: null,
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];
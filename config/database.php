<?php

$appEnv = $_ENV['APP_ENV'] ?? getenv('APP_ENV') ?: 'development';

if ($appEnv === 'production') {
    return [
        'driver' => $_ENV['DB_CONNECTION'] ?? getenv('DB_CONNECTION') ?: 'mysql',
        'host' => $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?: 'localhost',
        'port' => $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?: '3306',
        'database' => $_ENV['DB_DATABASE'] ?? getenv('DB_DATABASE') ?: '',
        'username' => $_ENV['DB_USERNAME'] ?? getenv('DB_USERNAME') ?: null,
        'password' => $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD') ?: null,
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    ];
} else {
    return [
        'driver' => 'sqlite',
        'host' => 'localhost',
        'port' => '3306',
        'database' => __DIR__ . '/../database/banco.sqlite',
        'username' => null,
        'password' => null,
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    ];
}
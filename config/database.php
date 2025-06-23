<?php

return [
    'driver' => $_ENV['DB_CONNECTION'] ?? getenv('DB_CONNECTION') ?: 'sqlite',
    'host' => $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?: 'localhost',
    'port' => $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?: '3306',
    'database' => $_ENV['DB_DATABASE'] ?? getenv('DB_DATABASE') ?: __DIR__ . '/../database/banco.sqlite',
    'username' => $_ENV['DB_USERNAME'] ?? getenv('DB_USERNAME') ?: null,
    'password' => $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD') ?: null,
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];
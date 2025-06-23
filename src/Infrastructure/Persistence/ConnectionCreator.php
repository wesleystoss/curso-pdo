<?php

namespace Alura\Pdo\Infrastructure\Persistence;

use PDO;

class ConnectionCreator
{
    public static function createConnection(): PDO
    {
        $config = require __DIR__ . '/../../../config/database.php';

        if ($config['driver'] === 'mysql') {
            $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']}";
            $pdo = new PDO($dsn, $config['username'], $config['password']);
        } else {
            $pdo = new PDO('sqlite:' . $config['database']);
        }

        foreach ($config['options'] as $option => $value) {
            $pdo->setAttribute($option, $value);
        }

        return $pdo;
    }
}
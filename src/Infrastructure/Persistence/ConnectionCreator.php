<?php

namespace Alura\Pdo\Infrastructure\Persistence;

use PDO;

class ConnectionCreator
{
    public static function createConnection(): PDO
    {
        $config = require __DIR__ . '/../../../config/database.php';
        return new PDO('sqlite:' . $config['database']);
    }
}
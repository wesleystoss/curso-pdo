<?php

namespace Alura\Pdo\Infrastructure\Persistence;

use PDO;

class ConnectionCreator
{
    public static function createConnection(): PDO
    {
        $config = require __DIR__ . '/../../../config/database.php';
        $pdo = new PDO('sqlite:' . $config['database']);
        
        // Aplicar opções de configuração
        foreach ($config['options'] as $option => $value) {
            $pdo->setAttribute($option, $value);
        }
        
        return $pdo;
    }
}
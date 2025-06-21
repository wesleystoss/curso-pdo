<?php

use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;

require_once __DIR__ . '/../vendor/autoload.php';

$pdo = ConnectionCreator::createConnection();

$sql = 'CREATE TABLE IF NOT EXISTS students (
    id INTEGER PRIMARY KEY,
    name TEXT,
    birth_date TEXT
);';

$pdo->exec($sql);

echo "Tabela students criada com sucesso!" . PHP_EOL; 
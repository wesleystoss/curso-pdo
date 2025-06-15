<?php

$databasePath = __DIR__ . '/banco.sqlite';
$pdo = new PDO(dsn:"sqlite:{$databasePath}");

echo 'Conectado com sucesso';

$pdo->exec(statement: 'CREATE TABLE IF NOT EXISTS students (
    id INTEGER PRIMARY KEY,
    name TEXT,
    birth_date TEXT
)');

echo 'Tabela criada com sucesso';
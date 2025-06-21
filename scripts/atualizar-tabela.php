<?php

use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;

require_once __DIR__ . '/../vendor/autoload.php';

$pdo = ConnectionCreator::createConnection();

// Verificar se as colunas já existem
$columns = $pdo->query("PRAGMA table_info(students)")->fetchAll(PDO::FETCH_ASSOC);
$columnNames = array_column($columns, 'name');

if (!in_array('cep', $columnNames)) {
    $pdo->exec('ALTER TABLE students ADD COLUMN cep TEXT');
    echo "Coluna 'cep' adicionada com sucesso!" . PHP_EOL;
}

if (!in_array('address', $columnNames)) {
    $pdo->exec('ALTER TABLE students ADD COLUMN address TEXT');
    echo "Coluna 'address' adicionada com sucesso!" . PHP_EOL;
}

echo "Tabela students atualizada com sucesso!" . PHP_EOL; 
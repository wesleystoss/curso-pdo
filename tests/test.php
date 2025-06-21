<?php

use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;

require_once __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/database.php';
$pdo = new PDO('sqlite:' . $config['database']);

// Criar tabela se não existir
$pdo->exec('CREATE TABLE IF NOT EXISTS students (
    id INTEGER PRIMARY KEY,
    name TEXT,
    birth_date TEXT
);');

$repository = new PdoStudentRepository($pdo);
$students = $repository->allStudents();
echo "Total de alunos: " . count($students) . "\n";
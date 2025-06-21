<?php

use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;

require_once 'vendor/autoload.php';

$pdo = new PDO('sqlite:banco.sqlite');

// Criar tabela se não existir
$pdo->exec('CREATE TABLE IF NOT EXISTS students (
    id INTEGER PRIMARY KEY,
    name TEXT,
    birth_date TEXT
);');

$repository = new PdoStudentRepository($pdo);
$students = $repository->allStudents();
echo "Total de alunos: " . count($students) . "\n";
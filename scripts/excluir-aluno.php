<?php

use Alura\Pdo\Domain\Model\Student;

require_once __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/database.php';
$pdo = new PDO('sqlite:' . $config['database']);

$statement = $pdo->query('DELETE FROM students');
$statement->execute();

echo "Todos os alunos foram excluídos com sucesso!" . PHP_EOL;

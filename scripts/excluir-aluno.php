<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;

require_once __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/database.php';
$pdo = new PDO('sqlite:' . $config['database']);
$repository = new PdoStudentRepository($pdo);

$studentList = $repository->allStudents();

foreach ($studentList as $student) {
    $repository->remove($student);
}

echo "Todos os alunos foram excluídos com sucesso!" . PHP_EOL;

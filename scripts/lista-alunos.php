<?php

use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;

require_once __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/database.php';
$pdo = new PDO('sqlite:' . $config['database']);
$repository = new PdoStudentRepository($pdo);

$studentDataList = $repository->allStudents();

if (count($studentDataList) === 0) {
    echo "Nenhum aluno encontrado" . PHP_EOL;
    exit();
}

foreach ($studentDataList as $student) {
    echo "------------------------" . PHP_EOL;
    $age = $student->birthDate()->diff(new \DateTimeImmutable())->y;
    echo "ID: " . $student->id() . PHP_EOL;
    echo "Nome: " . $student->name() . PHP_EOL;
    echo "Data de Nascimento: " . $student->birthDate()->format('d/m/Y') . PHP_EOL;
    echo "Idade: " . $age . " anos" . PHP_EOL;
    echo "------------------------" . PHP_EOL;

}
<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;

require_once __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/database.php';
$pdo = new PDO('sqlite:' . $config['database']);
$repository = new PdoStudentRepository($pdo);

echo "Digite o nome do aluno: ";
$name = trim(fgets(STDIN));
echo "Digite a data de nascimento (formato YYYY-MM-DD): ";
$birthDateInput = trim(fgets(STDIN));

try {
    $birthDate = new \DateTimeImmutable($birthDateInput);
} catch (\Exception $e) {
    echo "Data inválida! Use o formato YYYY-MM-DD" . PHP_EOL;
    exit(1);
}

$student = new Student(
    id: null,
    name: $name,
    birthDate: $birthDate
);

if ($repository->save($student)) {
    echo "Aluno incluído" . PHP_EOL;
} else {
    echo "Erro ao incluir aluno" . PHP_EOL;
}
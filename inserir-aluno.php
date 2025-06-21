<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;

require_once 'vendor/autoload.php';

$pdo = ConnectionCreator::createConnection();

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

$sqlInsert = "INSERT INTO students (name, birth_date) VALUES (?, ?);";
$statement = $pdo->prepare($sqlInsert);
$statement->bindValue(1, $student->name());
$statement->bindValue(2, $student->birthDate()->format('Y-m-d'));

if ($statement->execute()) {
    echo "Aluno incluído" . PHP_EOL;
} else {
    echo "Erro ao incluir aluno" . PHP_EOL;
}
<?php

use Alura\Pdo\Domain\Model\Student;

require_once 'vendor/autoload.php';

$databasePath = __DIR__ . '/banco.sqlite';
$pdo = new PDO('sqlite:' . $databasePath);

$student = new Student(
    id: null,
    name: "Patrícia Freitas",
    new \DateTimeImmutable(time: '1986-10-25');
);

$sqlInsert = "INSERT INTO students (name, birth_date) VALUES (:name, :birth_date);";
$statement = $pdo->prepare($sqlInsert);
$statement->bindValue(parameter: ':name', $student->name());
$statement->bindValue(parameter: ':birth_date', $student->birthDate()->format(format:'Y-m-d'));

if ($statement->execute()) {
    echo "Aluno incluído";
}
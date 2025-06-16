<?php

use Alura\Pdo\Domain\Model\Student;

require_once 'vendor/autoload.php';

$databasePath = __DIR__ . '/banco.sqlite';
$pdo = new PDO('sqlite:' . $databasePath);

$student = new Student(
    id: null,
    name: "Vinicius', ''); DROP TABLE students; -- Dias",
    new \DateTimeImmutable(time: '1997-10-15');
);

$sqlInsert = "INSERT INTO students (name, birth_date) VALUES (?, ?);";
$statement = $pdo->prepare($sqlInsert);
$statement->bindValue(parameter:1, $student->name());
$statement->bindValue(parameter:2, $student->birthDate()->format(format:'Y-m-d'));
echo $sqlInsert; exit();

var_dump($pdo->exec($sqlInsert));
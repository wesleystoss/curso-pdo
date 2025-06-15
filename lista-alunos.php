<?php

use Alura\Pdo\Domain\Model\Student;

require_once 'vendor/autoload.php';

$databasePath = __DIR__ . '/banco.sqlite';
$pdo = new PDO('sqlite:' . $databasePath);

$statement = $pdo->query('SELECT * FROM students');

$studentDataList = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach ($studentDataList as $student) {
    $birthDate = new \DateTimeImmutable($student['birth_date']);
    $age = $birthDate->diff(new \DateTimeImmutable())->y;
    echo $student['id'] . ' - ' . $student['name'] . ' - ' . $student['birth_date'] . ' - ' . $age . ' anos' . PHP_EOL;
}
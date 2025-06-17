<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;

require_once 'vendor/autoload.php';

$pdo = ConnectionCreator::createConnection();

$statement = $pdo->query('SELECT * FROM students');

$studentDataList = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach ($studentDataList as $student) {
    $birthDate = new \DateTimeImmutable($student['birth_date']);
    $age = $birthDate->diff(new \DateTimeImmutable())->y;
    echo "ID: " . $student['id'] . PHP_EOL;
    echo "Nome: " . $student['name'] . PHP_EOL;
    echo "Data de Nascimento: " . $student['birth_date'] . PHP_EOL;
    echo "Idade: " . $age . " anos" . PHP_EOL;
    echo "------------------------" . PHP_EOL;
    echo $student['id'] . ' - ' . $student['name'] . ' - ' . $student['birth_date'] . ' - ' . $age . ' anos' . PHP_EOL;
}
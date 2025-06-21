<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;

require_once __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/database.php';
$pdo = new PDO('sqlite:' . $config['database']);

$statement = $pdo->query('SELECT * FROM students');

$studentDataList = $statement->fetchAll(PDO::FETCH_ASSOC);

if (count($studentDataList) === 0) {
    echo "Nenhum aluno encontrado" . PHP_EOL;
    exit();
}

foreach ($studentDataList as $student) {
    echo "------------------------" . PHP_EOL;
    $birthDate = new \DateTimeImmutable($student['birth_date']);
    $age = $birthDate->diff(new \DateTimeImmutable())->y;
    echo "ID: " . $student['id'] . PHP_EOL;
    echo "Nome: " . $student['name'] . PHP_EOL;
    echo "Data de Nascimento: " . $student['birth_date'] . PHP_EOL;
    echo "Idade: " . $age . " anos" . PHP_EOL;
    echo "------------------------" . PHP_EOL;

}
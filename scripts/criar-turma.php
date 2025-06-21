<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;

require_once __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/database.php';
$pdo = new PDO('sqlite:' . $config['database']);
$studentRepository = new PdoStudentRepository($pdo);

$pdo->beginTransaction();

$aStudent = new Student(
    null,
    'Nico Steppat',
    new \DateTimeImmutable('1985-05-01')
);

$studentRepository->save($aStudent);

$anotherStudent = new Student(
    null,
    'Sergio Lopes',
    new \DateTimeImmutable('1985-05-01')
);

$studentRepository->save($anotherStudent);

$pdo->commit();
<?php

use Alura\Pdo\Domain\Model\Student;

require_once __DIR__ . '/../vendor/autoload.php';

$student = new Student(
    null,
    'Vinicius Dias',
    new \DateTimeImmutable('1997-10-15')
);

echo $student->age();

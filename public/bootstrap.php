<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;
use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;
use Alura\Pdo\Infrastructure\Web\StudentController;

$connection = ConnectionCreator::createConnection();
$repository = new PdoStudentRepository($connection);
$controller = new StudentController($repository);

return $controller->handleRequest();

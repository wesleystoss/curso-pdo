<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Carregar variáveis do .env
if (file_exists(dirname(__DIR__) . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();
}

use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;
use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;
use Alura\Pdo\Infrastructure\Web\ApiController;

try {
    $connection = ConnectionCreator::createConnection();
    $repository = new PdoStudentRepository($connection);
    $controller = new ApiController($repository);
    
    $controller->handleRequest();
} catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'error' => 'Erro interno do servidor',
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
} 
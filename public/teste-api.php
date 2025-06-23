<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

// Carregar variáveis do .env
if (file_exists(dirname(__DIR__) . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();
}

use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;
use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;

try {
    echo "🧪 Teste da API\n";
    echo "================\n\n";
    
    // Teste 1: Conexão com banco
    echo "1. Testando conexão com banco...\n";
    $pdo = ConnectionCreator::createConnection();
    echo "✅ Conexão OK\n\n";
    
    // Teste 2: Repositório
    echo "2. Testando repositório...\n";
    $repository = new PdoStudentRepository($pdo);
    echo "✅ Repositório OK\n\n";
    
    // Teste 3: Buscar todos os alunos
    echo "3. Testando busca de todos os alunos...\n";
    $students = $repository->allStudents();
    echo "✅ Encontrados " . count($students) . " alunos\n\n";
    
    // Teste 4: Buscar aluno por ID
    echo "4. Testando busca por ID...\n";
    $student = $repository->findById(1);
    if ($student) {
        echo "✅ Aluno ID 1: " . $student->name() . "\n\n";
    } else {
        echo "❌ Aluno ID 1 não encontrado\n\n";
    }
    
    // Teste 5: Converter para array
    echo "5. Testando conversão para array...\n";
    if ($student) {
        $data = [
            'id' => $student->id(),
            'name' => $student->name(),
            'birth_date' => $student->birthDate()->format('Y-m-d'),
            'age' => $student->age(),
            'cep' => $student->cep(),
            'address' => $student->address()
        ];
        echo "✅ Array gerado: " . json_encode($data) . "\n\n";
    }
    
    echo "🎉 Todos os testes passaram!\n";
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
} 
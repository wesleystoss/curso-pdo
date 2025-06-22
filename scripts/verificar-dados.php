<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;
use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;

try {
    $pdo = ConnectionCreator::createConnection();
    $repository = new PdoStudentRepository($pdo);
    
    echo "📊 Verificando dados do banco...\n\n";
    
    // Verificar todos os alunos
    $students = $repository->allStudents();
    echo "Total de alunos: " . count($students) . "\n\n";
    
    if (count($students) > 0) {
        echo "Primeiros 3 alunos:\n";
        foreach (array_slice($students, 0, 3) as $student) {
            echo "- ID: {$student->id()}, Nome: {$student->name()}, Idade: {$student->age()}\n";
        }
    } else {
        echo "Nenhum aluno encontrado no banco.\n";
    }
    
    // Testar busca por ID
    echo "\n🔍 Testando busca por ID...\n";
    for ($i = 1; $i <= 3; $i++) {
        $student = $repository->findById($i);
        if ($student) {
            echo "ID {$i}: {$student->name()}\n";
        } else {
            echo "ID {$i}: Não encontrado\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
} 
<?php

use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;
use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;

require_once __DIR__ . '/../vendor/autoload.php';

$connection = ConnectionCreator::createConnection();
$repository = new PdoStudentRepository($connection);

echo "=== Teste da Funcionalidade de Busca ===\n\n";

// Listar todos os alunos primeiro
echo "1. Listando todos os alunos:\n";
$allStudents = $repository->allStudents();
foreach ($allStudents as $student) {
    echo "   ID: {$student->id()}, Nome: {$student->name()}\n";
}
echo "\n";

// Teste de busca por ID
echo "2. Testando busca por ID:\n";
if (!empty($allStudents)) {
    $firstStudent = $allStudents[0];
    $foundStudent = $repository->findById($firstStudent->id());
    
    if ($foundStudent) {
        echo "   ✅ Aluno encontrado por ID {$firstStudent->id()}: {$foundStudent->name()}\n";
    } else {
        echo "   ❌ Aluno não encontrado por ID {$firstStudent->id()}\n";
    }
} else {
    echo "   ⚠️ Nenhum aluno cadastrado para testar busca por ID\n";
}
echo "\n";

// Teste de busca por nome
echo "3. Testando busca por nome:\n";
if (!empty($allStudents)) {
    $firstStudent = $allStudents[0];
    $namePart = substr($firstStudent->name(), 0, 3); // Primeiras 3 letras do nome
    $foundStudents = $repository->findByName($namePart);
    
    if (!empty($foundStudents)) {
        echo "   ✅ Alunos encontrados com '{$namePart}':\n";
        foreach ($foundStudents as $student) {
            echo "      - ID: {$student->id()}, Nome: {$student->name()}\n";
        }
    } else {
        echo "   ❌ Nenhum aluno encontrado com '{$namePart}'\n";
    }
} else {
    echo "   ⚠️ Nenhum aluno cadastrado para testar busca por nome\n";
}
echo "\n";

// Teste de busca por ID inexistente
echo "4. Testando busca por ID inexistente:\n";
$foundStudent = $repository->findById(99999);
if ($foundStudent === null) {
    echo "   ✅ Busca por ID inexistente retornou null (correto)\n";
} else {
    echo "   ❌ Busca por ID inexistente retornou um aluno (incorreto)\n";
}
echo "\n";

// Teste de busca por nome inexistente
echo "5. Testando busca por nome inexistente:\n";
$foundStudents = $repository->findByName('NomeInexistente123');
if (empty($foundStudents)) {
    echo "   ✅ Busca por nome inexistente retornou array vazio (correto)\n";
} else {
    echo "   ❌ Busca por nome inexistente retornou resultados (incorreto)\n";
}
echo "\n";

echo "=== Teste concluído ===\n"; 
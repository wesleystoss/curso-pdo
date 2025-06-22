<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;

require_once __DIR__ . '/../vendor/autoload.php';

// Configurar banco de teste
$databasePath = __DIR__ . '/../database/banco-teste.sqlite';
$pdo = new PDO('sqlite:' . $databasePath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "=== TESTE DO REPOSITORY (BANCO DE TESTE MELHORADO) ===\n";
echo "Banco de teste: " . $databasePath . "\n\n";

// Limpar banco de teste antes de começar
echo "Limpando banco de teste...\n";
if (file_exists($databasePath)) {
    unlink($databasePath);
}
touch($databasePath);

// Criar tabela
$pdo->exec('
    CREATE TABLE IF NOT EXISTS students (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        birth_date TEXT NOT NULL,
        cep TEXT,
        address TEXT
    );
');

$repository = new PdoStudentRepository($pdo);

// Teste 1: Inserir alunos
echo "1. Testando inserção de alunos...\n";
$student1 = new Student(null, 'João Silva', new \DateTimeImmutable('1990-05-15'));
$student2 = new Student(null, 'Maria Santos', new \DateTimeImmutable('1985-12-20'));
$student3 = new Student(null, 'Pedro Costa', new \DateTimeImmutable('1995-08-10'));

$result1 = $repository->save($student1);
$result2 = $repository->save($student2);
$result3 = $repository->save($student3);

echo "Inserção 1: " . ($result1 ? "SUCESSO" : "FALHA") . "\n";
echo "Inserção 2: " . ($result2 ? "SUCESSO" : "FALHA") . "\n";
echo "Inserção 3: " . ($result3 ? "SUCESSO" : "FALHA") . "\n\n";

// Teste 2: Listar todos os alunos
echo "2. Testando listagem de todos os alunos...\n";
$allStudents = $repository->allStudents();
echo "Total de alunos encontrados: " . count($allStudents) . "\n";

foreach ($allStudents as $student) {
    echo "- ID: {$student->id()}, Nome: {$student->name()}, Nascimento: {$student->birthDate()->format('Y-m-d')}\n";
}
echo "\n";

// Teste 3: Buscar alunos por data de nascimento
echo "3. Testando busca por data de nascimento...\n";
$birthDate = new \DateTimeImmutable('1990-05-15');
$studentsByBirth = $repository->studentsBirthAt($birthDate);
echo "Alunos nascidos em 1990-05-15: " . count($studentsByBirth) . "\n";

foreach ($studentsByBirth as $student) {
    echo "- ID: {$student->id()}, Nome: {$student->name()}\n";
}
echo "\n";

// Teste 4: Atualizar um aluno
echo "4. Testando atualização de aluno...\n";
if (!empty($allStudents)) {
    $firstStudent = $allStudents[0];
    $studentToUpdate = new Student(
        $firstStudent->id(),
        'João Silva Atualizado',
        new \DateTimeImmutable('1990-05-15')
    );
    
    $updateResult = $repository->save($studentToUpdate);
    echo "Atualização: " . ($updateResult ? "SUCESSO" : "FALHA") . "\n";
    
    // Verificar se foi atualizado
    $updatedStudents = $repository->allStudents();
    foreach ($updatedStudents as $student) {
        if ($student->id() == $firstStudent->id()) {
            echo "Nome atualizado: {$student->name()}\n";
            break;
        }
    }
}
echo "\n";

// Teste 5: Remover um aluno
echo "5. Testando remoção de aluno...\n";
if (!empty($allStudents)) {
    $studentToRemove = new Student($allStudents[0]->id(), '', new \DateTimeImmutable());
    $removeResult = $repository->remove($studentToRemove);
    echo "Remoção: " . ($removeResult ? "SUCESSO" : "FALHA") . "\n";
    
    // Verificar se foi removido
    $remainingStudents = $repository->allStudents();
    echo "Alunos restantes: " . count($remainingStudents) . "\n";
}
echo "\n";

echo "=== FIM DOS TESTES ===\n";
echo "Banco de teste mantido em: " . $databasePath . "\n";
echo "Para limpar completamente, execute: removeTestDatabase();\n"; 
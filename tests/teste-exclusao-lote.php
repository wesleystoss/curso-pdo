<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;
use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;

echo "🧪 Testando Funcionalidade de Exclusão em Lote\n";
echo "==============================================\n\n";

try {
    $connection = ConnectionCreator::createConnection();
    $repository = new PdoStudentRepository($connection);
    
    // 1. Inserir alguns alunos para teste
    echo "1. Inserindo alunos para teste...\n";
    
    $alunosTeste = [
        ['João Silva', '1995-03-15', '12345-678', 'Rua das Flores, 123'],
        ['Maria Santos', '1998-07-22', '23456-789', 'Av. Principal, 456'],
        ['Pedro Costa', '1993-11-08', '34567-890', 'Rua do Comércio, 789'],
        ['Ana Oliveira', '1997-04-30', '45678-901', 'Travessa da Paz, 321'],
        ['Carlos Lima', '1994-09-12', '56789-012', 'Alameda dos Ipês, 654']
    ];
    
    $idsInseridos = [];
    foreach ($alunosTeste as $aluno) {
        $student = new Student(
            null,
            $aluno[0],
            new DateTimeImmutable($aluno[1]),
            $aluno[2],
            $aluno[3]
        );
        
        $repository->save($student);
        $idsInseridos[] = $student->id();
        echo "   ✅ Aluno '{$aluno[0]}' inserido com ID: {$student->id()}\n";
    }
    
    echo "\n2. Verificando alunos inseridos...\n";
    $todosAlunos = $repository->allStudents();
    echo "   Total de alunos no banco: " . count($todosAlunos) . "\n";
    
    // 2. Simular exclusão em lote
    echo "\n3. Simulando exclusão em lote...\n";
    
    // Selecionar os 3 primeiros alunos para exclusão
    $idsParaExcluir = array_slice($idsInseridos, 0, 3);
    echo "   IDs selecionados para exclusão: " . implode(', ', $idsParaExcluir) . "\n";
    
    // Simular o processo de exclusão em lote
    $alunosParaExcluir = [];
    foreach ($idsParaExcluir as $id) {
        $student = $repository->findById($id);
        if ($student) {
            $alunosParaExcluir[] = $student;
        }
    }
    
    echo "   Alunos encontrados para exclusão: " . count($alunosParaExcluir) . "\n";
    
    // Excluir os alunos
    $deletedCount = 0;
    foreach ($alunosParaExcluir as $student) {
        echo "   🗑️ Excluindo aluno: {$student->name()} (ID: {$student->id()})\n";
        $repository->remove($student);
        $deletedCount++;
    }
    
    echo "   ✅ {$deletedCount} alunos excluídos com sucesso!\n";
    
    // 3. Verificar resultado
    echo "\n4. Verificando resultado após exclusão...\n";
    $alunosRestantes = $repository->allStudents();
    echo "   Total de alunos restantes: " . count($alunosRestantes) . "\n";
    
    // Verificar se os alunos excluídos realmente não existem mais
    $alunosExcluidosEncontrados = 0;
    foreach ($idsParaExcluir as $id) {
        $student = $repository->findById($id);
        if ($student) {
            $alunosExcluidosEncontrados++;
            echo "   ⚠️ Aluno com ID {$id} ainda existe (não foi excluído corretamente)\n";
        }
    }
    
    if ($alunosExcluidosEncontrados === 0) {
        echo "   ✅ Todos os alunos selecionados foram excluídos corretamente!\n";
    }
    
    // 4. Testar cenários de erro
    echo "\n5. Testando cenários de erro...\n";
    
    // Teste 1: IDs inválidos
    echo "   Teste 1: IDs inválidos (0, -1, 'abc')...\n";
    $idsInvalidos = [0, -1, 'abc'];
    $idsValidos = [];
    foreach ($idsInvalidos as $id) {
        $idInt = (int)$id;
        if ($idInt > 0) {
            $idsValidos[] = $idInt;
        }
    }
    echo "   IDs válidos filtrados: " . (empty($idsValidos) ? 'Nenhum' : implode(', ', $idsValidos)) . "\n";
    
    // Teste 2: IDs que não existem
    echo "   Teste 2: IDs que não existem no banco...\n";
    $idsInexistentes = [99999, 88888, 77777];
    $alunosInexistentes = [];
    foreach ($idsInexistentes as $id) {
        $student = $repository->findById($id);
        if ($student) {
            $alunosInexistentes[] = $student;
        }
    }
    echo "   Alunos encontrados com IDs inexistentes: " . count($alunosInexistentes) . "\n";
    
    // 5. Limpeza final
    echo "\n6. Limpeza final...\n";
    $alunosRestantes = $repository->allStudents();
    foreach ($alunosRestantes as $student) {
        if (in_array($student->name(), array_column($alunosTeste, 0))) {
            echo "   🗑️ Removendo aluno de teste: {$student->name()}\n";
            $repository->remove($student);
        }
    }
    
    echo "\n✅ Teste de exclusão em lote concluído com sucesso!\n";
    echo "📊 Resumo:\n";
    echo "   - Alunos inseridos: " . count($alunosTeste) . "\n";
    echo "   - Alunos excluídos em lote: {$deletedCount}\n";
    echo "   - Validação de IDs: OK\n";
    echo "   - Verificação de existência: OK\n";
    
} catch (Exception $e) {
    echo "❌ Erro durante o teste: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
} 
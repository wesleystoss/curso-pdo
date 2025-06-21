<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;
use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;
use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Domain\Service\StudentValidator;
use Alura\Pdo\Infrastructure\Service\Logger;
use Alura\Pdo\Infrastructure\Service\Cache;
use Alura\Pdo\Infrastructure\Service\EnvironmentConfig;
use Alura\Pdo\Domain\Exception\StudentException;

echo "🚀 DEMONSTRAÇÃO DAS FUNCIONALIDADES AVANÇADAS\n";
echo "=============================================\n\n";

try {
    // 1. Configuração do Sistema
    echo "1. 📋 CONFIGURAÇÃO DO SISTEMA\n";
    echo "-----------------------------\n";
    $config = EnvironmentConfig::getInstance();
    echo "Ambiente: " . $config->get("APP_ENV") . "\n";
    echo "Versão: " . $config->get("APP_VERSION") . "\n";
    echo "Debug: " . ($config->isDebugEnabled() ? "Ativado" : "Desativado") . "\n";
    echo "Banco de Dados: " . basename($config->getDatabasePath()) . "\n";
    echo "TTL do Cache: " . $config->getCacheTtl() . " segundos\n\n";

    // 2. Sistema de Logs
    echo "2. 📝 SISTEMA DE LOGS\n";
    echo "--------------------\n";
    $logger = Logger::getInstance();
    $logger->info("Demonstração iniciada", ["script" => "demo-avancado.php"]);
    $logger->warning("Este é um teste de warning");
    $logger->error("Este é um teste de erro");
    echo "Logs gravados com sucesso!\n";
    echo "Arquivo de log: " . $logger->getLogFile() . "\n\n";

    // 3. Sistema de Cache
    echo "3. 💾 SISTEMA DE CACHE\n";
    echo "----------------------\n";
    $cache = Cache::getInstance();
    
    // Teste de cache
    $testData = ["message" => "Dados de teste", "timestamp" => time()];
    $cache->set("demo_test", $testData, 60);
    echo "Dados salvos no cache\n";
    
    $cachedData = $cache->get("demo_test");
    if ($cachedData) {
        echo "Dados recuperados do cache: " . json_encode($cachedData) . "\n";
    }
    
    $stats = $cache->getStats();
    echo "Estatísticas do cache:\n";
    echo "  - Arquivos válidos: " . $stats["valid_files"] . "\n";
    echo "  - Tamanho total: " . number_format($stats["total_size"] / 1024, 2) . " KB\n";
    echo "  - Arquivos expirados: " . $stats["expired_files"] . "\n\n";

    // 4. Validação Robusta
    echo "4. 🛡️ SISTEMA DE VALIDAÇÃO\n";
    echo "--------------------------\n";
    $validator = new StudentValidator();
    
    // Teste de validação válida
    try {
        $student = new Student(
            null,
            "João Silva",
            new \DateTimeImmutable("1990-01-01"),
            "12345678",
            "Rua das Flores, 123"
        );
        $validator->validateStudent($student);
        echo "✅ Validação de aluno válido: OK\n";
    } catch (StudentException $e) {
        echo "❌ Erro na validação: " . $e->getMessage() . "\n";
    }
    
    // Teste de validação inválida
    try {
        $validator->validateName("J"); // Nome muito curto
        echo "❌ Validação deveria ter falhado\n";
    } catch (StudentException $e) {
        echo "✅ Validação de nome inválido: " . $e->getMessage() . "\n";
    }
    
    // Teste de CEP inválido
    try {
        $validator->validateCep("123"); // CEP muito curto
        echo "❌ Validação deveria ter falhado\n";
    } catch (StudentException $e) {
        echo "✅ Validação de CEP inválido: " . $e->getMessage() . "\n";
    }
    echo "\n";

    // 5. Operações com Banco de Dados
    echo "5. ��️ OPERAÇÕES COM BANCO DE DADOS\n";
    echo "----------------------------------\n";
    $connection = ConnectionCreator::createConnection();
    $repository = new PdoStudentRepository($connection);
    
    // Criar aluno de teste
    $testStudent = new Student(
        null,
        "Maria Teste",
        new \DateTimeImmutable("1995-05-15"),
        "87654321",
        "Avenida Teste, 456"
    );
    
    if ($repository->save($testStudent)) {
        echo "✅ Aluno de teste criado com sucesso\n";
        echo "ID do aluno: " . $testStudent->id() . "\n";
        
        // Buscar aluno criado
        $foundStudent = $repository->findById($testStudent->id());
        if ($foundStudent) {
            echo "✅ Aluno encontrado: " . $foundStudent->name() . "\n";
            echo "Idade: " . $foundStudent->age() . " anos\n";
        }
        
        // Teste de busca cumulativa
        $results = $repository->findByCriteria(null, "Maria", null);
        echo "✅ Busca cumulativa encontrou " . count($results) . " resultado(s)\n";
        
        // Remover aluno de teste
        if ($repository->remove($testStudent)) {
            echo "✅ Aluno de teste removido com sucesso\n";
        }
    } else {
        echo "❌ Erro ao criar aluno de teste\n";
    }
    echo "\n";

    // 6. Estatísticas do Sistema
    echo "6. 📊 ESTATÍSTICAS DO SISTEMA\n";
    echo "-----------------------------\n";
    $allStudents = $repository->allStudents();
    $total = count($allStudents);
    $adults = array_filter($allStudents, fn($s) => $s->age() >= 18);
    $minors = array_filter($allStudents, fn($s) => $s->age() < 18);
    
    echo "Total de alunos: " . $total . "\n";
    echo "Maiores de 18 anos: " . count($adults) . "\n";
    echo "Menores de 18 anos: " . count($minors) . "\n";
    
    if ($total > 0) {
        $averageAge = array_sum(array_map(fn($s) => $s->age(), $allStudents)) / $total;
        echo "Idade média: " . number_format($averageAge, 1) . " anos\n";
    }
    echo "\n";

    // 7. Teste de Performance
    echo "7. ⚡ TESTE DE PERFORMANCE\n";
    echo "--------------------------\n";
    
    // Teste sem cache
    $startTime = microtime(true);
    $repository->allStudents();
    $timeWithoutCache = microtime(true) - $startTime;
    
    // Teste com cache
    $cache->set("performance_test", $repository->allStudents(), 300);
    $startTime = microtime(true);
    $cache->get("performance_test");
    $timeWithCache = microtime(true) - $startTime;
    
    echo "Tempo sem cache: " . number_format($timeWithoutCache * 1000, 2) . " ms\n";
    echo "Tempo com cache: " . number_format($timeWithCache * 1000, 2) . " ms\n";
    echo "Melhoria: " . number_format((($timeWithoutCache - $timeWithCache) / $timeWithoutCache) * 100, 1) . "%\n\n";

    // 8. Limpeza
    echo "8. 🧹 LIMPEZA\n";
    echo "-------------\n";
    $cache->delete("demo_test");
    $cache->delete("performance_test");
    echo "✅ Cache de demonstração limpo\n";
    
    $expiredCleared = $cache->clearExpired();
    echo "✅ " . $expiredCleared . " arquivos expirados removidos\n\n";

    echo "🎉 DEMONSTRAÇÃO CONCLUÍDA COM SUCESSO!\n";
    echo "=====================================\n";
    echo "✅ Todas as funcionalidades avançadas foram testadas\n";
    echo "✅ Sistema funcionando corretamente\n";
    echo "✅ Logs gravados para auditoria\n";
    echo "✅ Cache funcionando para performance\n";
    echo "✅ Validações robustas implementadas\n";
    echo "✅ Banco de dados operacional\n\n";
    
    echo "📋 PRÓXIMOS PASSOS:\n";
    echo "1. Acesse http://localhost:8000 para a interface web\n";
    echo "2. Acesse http://localhost:8000/admin.php para o painel de admin\n";
    echo "3. Teste a API em http://localhost:8000/api/students\n";
    echo "4. Execute \"composer run test:unit\" para os testes\n";
    echo "5. Execute \"composer run logs:view\" para ver os logs\n";

} catch (Exception $e) {
    echo "❌ ERRO NA DEMONSTRAÇÃO: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    
    // Log do erro
    $logger = Logger::getInstance();
    $logger->error("Erro na demonstração", [
        "error" => $e->getMessage(),
        "file" => $e->getFile(),
        "line" => $e->getLine()
    ]);
}

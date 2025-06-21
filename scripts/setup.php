<?php

/**
 * Script de Setup do Projeto
 * Configura o ambiente inicial e cria as estruturas necessárias
 */

echo "=== SETUP DO PROJETO CURSO PDO ===\n\n";

// Verificar se o Composer está instalado
if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    echo "❌ Composer não está instalado. Execute: composer install\n";
    exit(1);
}

echo "✅ Composer instalado\n";

// Criar diretórios necessários
$directories = [
    __DIR__ . '/../database',
    __DIR__ . '/../config',
    __DIR__ . '/../tests',
    __DIR__ . '/../scripts'
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
        echo "📁 Diretório criado: " . basename($dir) . "\n";
    } else {
        echo "✅ Diretório existe: " . basename($dir) . "\n";
    }
}

// Criar banco de dados se não existir
$databasePath = __DIR__ . '/../database/banco.sqlite';
if (!file_exists($databasePath)) {
    touch($databasePath);
    echo "🗄️  Banco de dados criado: banco.sqlite\n";
} else {
    echo "✅ Banco de dados existe: banco.sqlite\n";
}

// Criar tabela students
try {
    $config = require __DIR__ . '/../config/database.php';
    $pdo = new PDO('sqlite:' . $config['database']);
    
    $sql = 'CREATE TABLE IF NOT EXISTS students (
        id INTEGER PRIMARY KEY,
        name TEXT,
        birth_date TEXT
    )';
    
    $pdo->exec($sql);
    echo "✅ Tabela 'students' criada/verificada\n";
    
} catch (Exception $e) {
    echo "❌ Erro ao criar tabela: " . $e->getMessage() . "\n";
    exit(1);
}

// Verificar permissões
$writablePaths = [
    __DIR__ . '/../database',
    __DIR__ . '/../config'
];

foreach ($writablePaths as $path) {
    if (is_writable($path)) {
        echo "✅ Permissões OK: " . basename($path) . "\n";
    } else {
        echo "⚠️  Verificar permissões: " . basename($path) . "\n";
    }
}

echo "\n=== SETUP CONCLUÍDO ===\n";
echo "🎉 Projeto configurado com sucesso!\n\n";
echo "Comandos disponíveis:\n";
echo "  composer run listar    - Listar alunos\n";
echo "  composer run inserir   - Inserir aluno\n";
echo "  composer run excluir   - Excluir alunos\n";
echo "  composer run test      - Executar testes\n";
echo "  composer run test:clean - Limpar banco de teste\n\n"; 
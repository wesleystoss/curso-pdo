<?php

/**
 * Script para limpar o banco de dados de teste
 */

echo "🧹 Limpando banco de dados de teste...\n";

$databasePath = __DIR__ . '/../database/banco-teste.sqlite';

if (file_exists($databasePath)) {
    unlink($databasePath);
    echo "✅ Banco de teste removido\n";
} else {
    echo "ℹ️  Banco de teste não existe\n";
}

// Recriar o banco
touch($databasePath);
echo "✅ Banco de teste recriado\n";

// Criar tabela
try {
    $pdo = new PDO('sqlite:' . $databasePath);
    
    $sql = 'CREATE TABLE IF NOT EXISTS students (
        id INTEGER PRIMARY KEY,
        name TEXT,
        birth_date TEXT,
        cep TEXT,
        address TEXT
    )';
    
    $pdo->exec($sql);
    echo "✅ Tabela 'students' criada no banco de teste\n";
    
} catch (Exception $e) {
    echo "❌ Erro ao criar tabela: " . $e->getMessage() . "\n";
    exit(1);
}

echo "🎉 Banco de teste limpo e configurado!\n"; 
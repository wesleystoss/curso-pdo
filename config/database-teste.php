<?php

/**
 * Configuração para banco de teste
 * Este arquivo define as configurações específicas para testes
 */

// Configuração do banco de teste
define('TEST_DATABASE_PATH', __DIR__ . '/../database/banco-teste.sqlite');

// Função para criar conexão de teste
function createTestConnection(): PDO
{
    $pdo = new PDO("sqlite:" . TEST_DATABASE_PATH);
    
    // Criar tabela de teste se não existir
    $pdo->exec('CREATE TABLE IF NOT EXISTS students (
        id INTEGER PRIMARY KEY,
        name TEXT,
        birth_date TEXT,
        cep TEXT,
        address TEXT
    )');
    
    return $pdo;
}

// Função para limpar dados de teste
function clearTestDatabase(): void
{
    $pdo = new PDO("sqlite:" . TEST_DATABASE_PATH);
    
    // Criar tabela se não existir
    $pdo->exec('CREATE TABLE IF NOT EXISTS students (
        id INTEGER PRIMARY KEY,
        name TEXT,
        birth_date TEXT,
        cep TEXT,
        address TEXT
    )');
    
    // Limpar dados
    $pdo->exec('DELETE FROM students');
    
    // Resetar sequência se a tabela sqlite_sequence existir
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='sqlite_sequence'");
    if ($stmt->fetch()) {
        $pdo->exec('DELETE FROM sqlite_sequence WHERE name="students"');
    }
}

// Função para remover arquivo de banco de teste
function removeTestDatabase(): void
{
    if (file_exists(TEST_DATABASE_PATH)) {
        unlink(TEST_DATABASE_PATH);
    }
}

return [
    'driver' => 'sqlite',
    'database' => __DIR__ . '/../database/banco-teste.sqlite',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
]; 
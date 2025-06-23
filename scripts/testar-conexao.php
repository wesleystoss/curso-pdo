<?php
// Script para testar conexão com o banco MySQL e contar registros na tabela Students
require_once __DIR__ . '/../vendor/autoload.php';

// Carregar variáveis do .env
if (file_exists(dirname(__DIR__) . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();
}

$config = require __DIR__ . '/../config/database.php';

try {
    if ($config['driver'] !== 'mysql') {
        throw new Exception('O driver do banco não está configurado para MySQL.');
    }
    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        $config['host'],
        $config['port'],
        $config['database']
    );
    $pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
    echo "✅ Conexão com o banco MySQL estabelecida com sucesso!<br>";
    $stmt = $pdo->query('SELECT COUNT(*) as total FROM Students');
    $row = $stmt->fetch();
    echo "Total de registros na tabela Students: <strong>{$row['total']}</strong><br>";
} catch (Exception $e) {
    echo "❌ Erro ao conectar ou consultar o banco: " . $e->getMessage();
} 
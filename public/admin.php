<?php

require_once 'bootstrap.php';

$message = '';
$error = '';
$action = $_POST['action'] ?? '';

// Processar ações administrativas
if ($action === 'clear_database') {
    try {
        $pdo = \Alura\Pdo\Infrastructure\Persistence\ConnectionCreator::createConnection();
        $pdo->exec('DELETE FROM students');
        $message = '✅ Banco de dados limpo com sucesso!';
    } catch (Exception $e) {
        $error = '❌ Erro ao limpar banco de dados: ' . $e->getMessage();
    }
}

// Informações do sistema
$dbPath = __DIR__ . '/../src/Infrastructure/Persistence/banco.sqlite';
$dbSize = file_exists($dbPath) ? filesize($dbPath) : 0;
$dbSizeFormatted = $dbSize > 1024 * 1024 ? round($dbSize / (1024 * 1024), 2) . ' MB' : round($dbSize / 1024, 2) . ' KB';

$pageTitle = 'Administração - Gerenciador de Alunos';
include 'includes/header.php';
?>

<style>
.fixed-admin-header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    z-index: 1000;
    padding: 20px 0 10px 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.fixed-admin-header h1 {
    margin: 0;
    font-size: 2em;
    text-align: center;
}
.admin-content {
    margin-top: 90px;
}
.api-section pre {
    background: #222;
    color: #fff;
    padding: 15px;
    border-radius: 8px;
    font-size: 0.95em;
    overflow-x: auto;
}
</style>

<div class="admin-content">
    <?php if ($message): ?>
        <div class="message success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="section">
        <h2>🔌 APIs Disponíveis</h2>
        <div class="api-section">
            <strong>Buscar Alunos (GET):</strong>
            <pre>GET /public/api.php?name=João&amp;cep=12345-678</pre>
            <strong>Exemplo de resposta:</strong>
            <pre>{
  "success": true,
  "data": [
    { "id": 1, "name": "João", "birth_date": "2000-01-01", "cep": "12345-678", "address": "Rua Exemplo, 123" }
  ]
}</pre>
            <strong>Inserir Aluno (POST):</strong>
            <pre>POST /public/api.php
{
  "name": "Maria",
  "birth_date": "2001-02-03",
  "cep": "98765-432",
  "address": "Av. Teste, 456"
}</pre>
        </div>
    </div>

    <div class="section">
        <h2>🧹 Manutenção</h2>
        <form method="POST" onsubmit="return confirm('Tem certeza que deseja limpar todo o banco de dados? Esta ação não pode ser desfeita!')">
            <input type="hidden" name="action" value="clear_database">
            <button type="submit" class="btn btn-danger">🗑️ Limpar Banco de Dados</button>
        </form>
    </div>

    <div class="section">
        <h2>ℹ️ Informações do Sistema</h2>
        <div class="system-info">
            <div class="info-item"><strong>Versão do PHP:</strong> <?= PHP_VERSION ?></div>
            <div class="info-item"><strong>Extensão PDO SQLite:</strong> <?= extension_loaded('pdo_sqlite') ? '✅ Disponível' : '❌ Não disponível' ?></div>
            <div class="info-item"><strong>Diretório do Banco:</strong> <?= realpath(__DIR__ . '/../src/Infrastructure/Persistence/') ?></div>
            <div class="info-item"><strong>Tamanho do Banco:</strong> <?= $dbSizeFormatted ?></div>
            <div class="info-item"><strong>Última Modificação:</strong> <?= file_exists($dbPath) ? date('d/m/Y H:i:s', filemtime($dbPath)) : 'N/A' ?></div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

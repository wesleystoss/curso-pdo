<?php

require_once 'bootstrap.php';

use Alura\Pdo\Infrastructure\Service\EnvironmentConfig;
use Alura\Pdo\Infrastructure\Service\AppLogger;
use Alura\Pdo\Infrastructure\Service\Cache;
use Alura\Pdo\Infrastructure\Service\NotificationService;

$message = '';
$error = '';
$action = $_POST['action'] ?? '';
$config = EnvironmentConfig::getInstance();
$logger = AppLogger::getInstance();
$cache = Cache::getInstance();
$notifications = NotificationService::getInstance();

// Processar ações administrativas
if ($action === 'clear_database') {
    try {
        $pdo = \Alura\Pdo\Infrastructure\Persistence\ConnectionCreator::createConnection();
        $pdo->exec('DELETE FROM students');
        $message = '✅ Banco de dados limpo com sucesso!';
        $logger->info('Banco de dados limpo via admin');
    } catch (Exception $e) {
        $error = '❌ Erro ao limpar banco de dados: ' . $e->getMessage();
        $logger->error('Erro ao limpar banco: ' . $e->getMessage());
    }
} elseif ($action === 'test_notifications') {
    try {
        $results = $notifications->testNotifications();
        if ($results['email'] && $results['browser']) {
            $message = '✅ Teste de notificações realizado com sucesso!';
        } elseif ($results['email']) {
            $message = '✅ Email enviado com sucesso! Notificação do navegador pode estar desabilitada.';
        } elseif ($results['browser']) {
            $message = '✅ Notificação do navegador enviada! Email pode estar desabilitado.';
        } else {
            $error = '❌ Falha no teste de notificações. Verifique as configurações.';
        }
        $logger->info('Teste de notificações realizado', $results);
    } catch (Exception $e) {
        $error = '❌ Erro no teste de notificações: ' . $e->getMessage();
        $logger->error('Erro no teste de notificações: ' . $e->getMessage());
    }
} elseif ($action === 'clear_cache') {
    try {
        $cache->clear();
        $message = '✅ Cache limpo com sucesso!';
        $logger->info('Cache limpo via admin');
    } catch (Exception $e) {
        $error = '❌ Erro ao limpar cache: ' . $e->getMessage();
        $logger->error('Erro ao limpar cache: ' . $e->getMessage());
    }
} elseif ($action === 'export_logs') {
    try {
        $logDir = __DIR__ . '/../logs/';
        $logFiles = glob($logDir . '*.log');
        $exportData = [];
        
        foreach ($logFiles as $file) {
            $exportData[basename($file)] = [
                'size' => filesize($file),
                'lines' => count(file($file)),
                'last_modified' => date('Y-m-d H:i:s', filemtime($file))
            ];
        }
        
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="logs_export_' . date('Y-m-d_H-i-s') . '.json"');
        echo json_encode($exportData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    } catch (Exception $e) {
        $error = '❌ Erro ao exportar logs: ' . $e->getMessage();
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
.api-section {
    margin-bottom: 30px;
}
.api-section pre {
    background: #1e1e1e;
    color: #d4d4d4;
    padding: 20px;
    border-radius: 8px;
    font-size: 0.9em;
    overflow-x: auto;
    border-left: 4px solid #667eea;
}
.api-section h4 {
    color: #667eea;
    margin: 15px 0 10px 0;
    font-size: 1.1em;
}
.api-method {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: bold;
    font-size: 0.8em;
    margin-right: 10px;
}
.api-method.get { background: #28a745; color: white; }
.api-method.post { background: #007bff; color: white; }
.api-method.put { background: #ffc107; color: black; }
.api-method.delete { background: #dc3545; color: white; }
.api-endpoint {
    font-family: 'Courier New', monospace;
    background: #f8f9fa;
    padding: 8px 12px;
    border-radius: 4px;
    border: 1px solid #dee2e6;
}
.test-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 15px;
}
.test-btn {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.9em;
    transition: all 0.3s ease;
}
.test-btn.get { background: #28a745; color: white; }
.test-btn.post { background: #007bff; color: white; }
.test-btn.put { background: #ffc107; color: black; }
.test-btn.delete { background: #dc3545; color: white; }
.test-btn:hover { opacity: 0.8; }
.system-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}
.stat-card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    border-left: 4px solid #667eea;
}
.stat-card h3 {
    margin: 0 0 15px 0;
    color: #333;
    font-size: 1.1em;
}
.stat-value {
    font-size: 2em;
    font-weight: bold;
    color: #667eea;
    margin-bottom: 5px;
}
.stat-label {
    color: #666;
    font-size: 0.9em;
}
</style>

<div class="admin-content">
    <?php if ($message): ?>
        <div class="message success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Estatísticas do Sistema -->
    <div class="section">
        <h2>📊 Estatísticas do Sistema</h2>
        <div class="system-stats">
            <div class="stat-card">
                <h3>💾 Banco de Dados</h3>
                <div class="stat-value"><?= $dbSizeFormatted ?></div>
                <div class="stat-label">Tamanho do arquivo</div>
            </div>
            <div class="stat-card">
                <h3>📝 Logs</h3>
                <div class="stat-value"><?= count(glob(__DIR__ . '/../logs/*.log')) ?></div>
                <div class="stat-label">Arquivos de log</div>
            </div>
            <div class="stat-card">
                <h3>⚙️ Cache</h3>
                <div class="stat-value"><?= $cache->getStats()['hits'] ?? 0 ?></div>
                <div class="stat-label">Hits de cache</div>
            </div>
            <div class="stat-card">
                <h3>🔔 Notificações</h3>
                <div class="stat-value"><?= $config->get('notifications_enabled') ? '✅' : '❌' ?></div>
                <div class="stat-label">Status</div>
            </div>
        </div>
    </div>

    <!-- APIs CRUD Completas -->
    <div class="section">
        <h2>🔌 APIs CRUD Completas</h2>
        
        <div class="api-section">
            <h3>📋 Listar Todos os Alunos</h3>
            <div class="api-method get">GET</div>
            <span class="api-endpoint">/public/api.php/students</span>
            <p>Retorna todos os alunos cadastrados no sistema.</p>
            <h4>Parâmetros de Query (opcionais):</h4>
            <pre>name=João&cep=12345-678&limit=10&offset=0</pre>
            <h4>Exemplo de Resposta:</h4>
            <pre>{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "João Silva",
      "birth_date": "2000-01-01",
      "age": 23,
      "cep": "12345-678",
      "address": "Rua Exemplo, 123"
    }
  ]
}</pre>
        </div>

        <div class="api-section">
            <h3>🔍 Buscar Aluno por ID</h3>
            <div class="api-method get">GET</div>
            <span class="api-endpoint">/public/api.php/students/{id}</span>
            <p>Retorna um aluno específico pelo ID.</p>
            <h4>Exemplo de Resposta:</h4>
            <pre>{
  "success": true,
  "data": {
    "id": 1,
    "name": "João Silva",
    "birth_date": "2000-01-01",
    "age": 23,
    "cep": "12345-678",
    "address": "Rua Exemplo, 123"
  }
}</pre>
        </div>

        <div class="api-section">
            <h3>➕ Criar Novo Aluno</h3>
            <div class="api-method post">POST</div>
            <span class="api-endpoint">/public/api.php/students</span>
            <p>Cria um novo aluno no sistema.</p>
            <h4>Corpo da Requisição (JSON):</h4>
            <pre>{
  "name": "Maria Silva",
  "birth_date": "2001-02-03",
  "cep": "98765-432",
  "address": "Av. Teste, 456"
}</pre>
            <h4>Exemplo de Resposta:</h4>
            <pre>{
  "success": true,
  "data": {
    "id": 2,
    "name": "Maria Silva",
    "birth_date": "2001-02-03",
    "age": 22,
    "cep": "98765-432",
    "address": "Av. Teste, 456"
  }
}</pre>
        </div>

        <div class="api-section">
            <h3>✏️ Atualizar Aluno</h3>
            <div class="api-method put">PUT</div>
            <span class="api-endpoint">/public/api.php/students/{id}</span>
            <p>Atualiza os dados de um aluno existente.</p>
            <h4>Corpo da Requisição (JSON):</h4>
            <pre>{
  "name": "João Silva Atualizado",
  "birth_date": "2000-01-01",
  "cep": "12345-999",
  "address": "Nova Rua, 999"
}</pre>
            <h4>Exemplo de Resposta:</h4>
            <pre>{
  "success": true,
  "data": {
    "id": 1,
    "name": "João Silva Atualizado",
    "birth_date": "2000-01-01",
    "age": 23,
    "cep": "12345-999",
    "address": "Nova Rua, 999"
  }
}</pre>
        </div>

        <div class="api-section">
            <h3>🗑️ Excluir Aluno</h3>
            <div class="api-method delete">DELETE</div>
            <span class="api-endpoint">/public/api.php/students/{id}</span>
            <p>Remove um aluno do sistema.</p>
            <h4>Exemplo de Resposta:</h4>
            <pre>{
  "success": true,
  "data": {
    "message": "Aluno excluído com sucesso"
  }
}</pre>
        </div>

        <div class="api-section">
            <h3>📊 Estatísticas da API</h3>
            <div class="api-method get">GET</div>
            <span class="api-endpoint">/public/api.php/stats</span>
            <p>Retorna estatísticas do sistema.</p>
            <h4>Exemplo de Resposta:</h4>
            <pre>{
  "success": true,
  "data": {
    "total_students": 150,
    "adults": 120,
    "minors": 30,
    "average_age": 25.5,
    "cache_stats": {
      "hits": 45,
      "misses": 12
    }
  }
}</pre>
        </div>
    </div>

    <!-- Teste de Notificações -->
    <div class="section">
        <h2>🔔 Teste de Notificações</h2>
        <p>Teste o sistema de notificações por email e navegador.</p>
        
        <div class="api-section">
            <h3>📧 Notificações por Email</h3>
            <p><strong>Email do Administrador:</strong> <?= $config->get('admin_email') ?: 'Não configurado' ?></p>
            <p><strong>Status:</strong> <?= $config->get('notifications_enabled') ? '✅ Ativado' : '❌ Desativado' ?></p>
            
            <form method="POST" style="margin-top: 15px;">
                <input type="hidden" name="action" value="test_notifications">
                <button type="submit" class="btn btn-primary">🧪 Testar Notificações</button>
            </form>
        </div>

        <div class="api-section">
            <h3>🌐 Notificações do Navegador</h3>
            <p>Para receber notificações do navegador, você precisa:</p>
            <ol>
                <li>Permitir notificações quando solicitado pelo navegador</li>
                <li>Estar em uma conexão HTTPS (ou localhost)</li>
                <li>Ter o navegador aberto</li>
            </ol>
            <button class="btn btn-secondary" onclick="requestNotificationPermission()">🔔 Solicitar Permissão</button>
        </div>
    </div>

    <!-- Ferramentas de Manutenção -->
    <div class="section">
        <h2>🛠️ Ferramentas de Manutenção</h2>
        
        <div class="api-section">
            <h3>🧹 Limpeza do Sistema</h3>
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <form method="POST" onsubmit="return confirm('Tem certeza que deseja limpar todo o banco de dados? Esta ação não pode ser desfeita!')">
                    <input type="hidden" name="action" value="clear_database">
                    <button type="submit" class="btn btn-danger">🗑️ Limpar Banco de Dados</button>
                </form>
                
                <form method="POST" onsubmit="return confirm('Tem certeza que deseja limpar o cache?')">
                    <input type="hidden" name="action" value="clear_cache">
                    <button type="submit" class="btn btn-warning">🧹 Limpar Cache</button>
                </form>
                
                <form method="POST">
                    <input type="hidden" name="action" value="export_logs">
                    <button type="submit" class="btn btn-info">📥 Exportar Logs</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Informações do Sistema -->
    <div class="section">
        <h2>ℹ️ Informações do Sistema</h2>
        <div class="system-info">
            <div class="info-item"><strong>Versão do PHP:</strong> <?= PHP_VERSION ?></div>
            <div class="info-item"><strong>Extensão PDO SQLite:</strong> <?= extension_loaded('pdo_sqlite') ? '✅ Disponível' : '❌ Não disponível' ?></div>
            <div class="info-item"><strong>Extensão cURL:</strong> <?= extension_loaded('curl') ? '✅ Disponível' : '❌ Não disponível' ?></div>
            <div class="info-item"><strong>Função mail():</strong> <?= function_exists('mail') ? '✅ Disponível' : '❌ Não disponível' ?></div>
            <div class="info-item"><strong>Diretório do Banco:</strong> <?= realpath(__DIR__ . '/../src/Infrastructure/Persistence/') ?></div>
            <div class="info-item"><strong>Tamanho do Banco:</strong> <?= $dbSizeFormatted ?></div>
            <div class="info-item"><strong>Última Modificação:</strong> <?= file_exists($dbPath) ? date('d/m/Y H:i:s', filemtime($dbPath)) : 'N/A' ?></div>
            <div class="info-item"><strong>Ambiente:</strong> <?= ucfirst($config->get('app_env')) ?></div>
            <div class="info-item"><strong>Debug:</strong> <?= $config->isDebugEnabled() ? 'Ativado' : 'Desativado' ?></div>
            <div class="info-item"><strong>Cache:</strong> <?= $config->get('cache_enabled') ? 'Ativo' : 'Inativo' ?></div>
            <div class="info-item"><strong>Notificações:</strong> <?= $config->get('notifications_enabled') ? 'Ativas' : 'Inativas' ?></div>
        </div>
    </div>
</div>

<script>
// Função para solicitar permissão de notificação
async function requestNotificationPermission() {
    if (!('Notification' in window)) {
        alert('Este navegador não suporta notificações');
        return;
    }
    
    if (Notification.permission === 'granted') {
        alert('Permissão de notificação já concedida!');
        return;
    }
    
    if (Notification.permission === 'denied') {
        alert('Permissão de notificação negada. Você pode habilitar manualmente nas configurações do navegador.');
        return;
    }
    
    const permission = await Notification.requestPermission();
    if (permission === 'granted') {
        alert('✅ Permissão de notificação concedida!');
        // Enviar notificação de teste
        new Notification('Teste de Notificação', {
            body: 'Notificação de teste funcionando!',
            icon: '/public/assets/images/notification-icon.png'
        });
    } else {
        alert('❌ Permissão de notificação negada.');
    }
}

// Verificar permissão de notificação ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    if ('Notification' in window && Notification.permission === 'granted') {
        console.log('Notificações habilitadas');
    }
});
</script>

<?php include 'includes/footer.php'; ?>

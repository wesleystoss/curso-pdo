<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Alura\Pdo\Infrastructure\Service\NotificationService;
use Alura\Pdo\Infrastructure\Service\EnvironmentConfig;
use Alura\Pdo\Infrastructure\Service\AppLogger;

echo "🧪 Teste do Sistema de Notificações\n";
echo "===================================\n\n";

$config = EnvironmentConfig::getInstance();
$logger = AppLogger::getInstance();
$notifications = NotificationService::getInstance();

echo "📋 Configurações:\n";
echo "- Ambiente: " . $config->get('app_env') . "\n";
echo "- Notificações habilitadas: " . ($config->get('notifications_enabled') ? 'Sim' : 'Não') . "\n";
echo "- Email do admin: " . ($config->get('admin_email') ?: 'Não configurado') . "\n";
echo "- Email do app: " . ($config->get('app_email') ?: 'Não configurado') . "\n\n";

if (!$config->get('notifications_enabled')) {
    echo "❌ Notificações estão desabilitadas no arquivo .env\n";
    echo "Para habilitar, defina: notifications_enabled=true\n\n";
    exit(1);
}

echo "🔔 Iniciando testes de notificação...\n\n";

// Teste 1: Notificação de novo aluno
echo "1️⃣ Testando notificação de novo aluno...\n";
try {
    $result = $notifications->notifyNewStudent('João Silva Teste', 'joao@teste.com');
    echo $result ? "✅ Sucesso\n" : "❌ Falha\n";
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}
echo "\n";

// Teste 2: Notificação de exclusão
echo "2️⃣ Testando notificação de exclusão...\n";
try {
    $result = $notifications->notifyStudentDeleted('Maria Silva Teste');
    echo $result ? "✅ Sucesso\n" : "❌ Falha\n";
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}
echo "\n";

// Teste 3: Notificação de erro do sistema
echo "3️⃣ Testando notificação de erro...\n";
try {
    $result = $notifications->notifySystemError('Erro de teste', 'Script de teste');
    echo $result ? "✅ Sucesso\n" : "❌ Falha\n";
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}
echo "\n";

// Teste 4: Email personalizado
echo "4️⃣ Testando email personalizado...\n";
try {
    $result = $notifications->sendEmail(
        $config->get('admin_email') ?: 'teste@example.com',
        '🧪 Teste Personalizado - ' . $config->get('app_name'),
        '<h2>Teste de Email Personalizado</h2><p>Este é um teste do sistema de notificações.</p>',
        ['template' => 'minimal']
    );
    echo $result ? "✅ Sucesso\n" : "❌ Falha\n";
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}
echo "\n";

// Teste 5: Notificação do navegador
echo "5️⃣ Testando notificação do navegador...\n";
try {
    $result = $notifications->sendBrowserNotification(
        'Teste do Navegador',
        'Esta é uma notificação de teste do sistema.',
        ['tag' => 'teste-script']
    );
    echo $result ? "✅ Sucesso\n" : "❌ Falha\n";
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}
echo "\n";

// Teste 6: Teste completo
echo "6️⃣ Executando teste completo...\n";
try {
    $results = $notifications->testNotifications();
    echo "Resultados:\n";
    echo "- Email: " . ($results['email'] ? '✅' : '❌') . "\n";
    echo "- Navegador: " . ($results['browser'] ? '✅' : '❌') . "\n";
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}
echo "\n";

echo "📊 Logs gerados:\n";
$logDir = __DIR__ . '/../logs/';
$logFiles = glob($logDir . '*.log');
foreach ($logFiles as $file) {
    $lines = count(file($file));
    echo "- " . basename($file) . ": $lines linhas\n";
}

echo "\n✅ Teste concluído!\n";
echo "💡 Dicas:\n";
echo "- Verifique sua caixa de entrada para emails de teste\n";
echo "- Consulte os logs em /logs/ para mais detalhes\n"; 
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Alura\Pdo\Infrastructure\Service\NotificationService;
use Alura\Pdo\Infrastructure\Service\EnvironmentConfig;

try {
    echo "🚀 Testando envio de email...\n";
    
    $config = EnvironmentConfig::getInstance();
    $notificationService = NotificationService::getInstance();
    
    // Verificar se email está habilitado
    if (!$config->get('notifications_email_enabled')) {
        echo "❌ Notificações por email estão desabilitadas no .env\n";
        echo "Configure NOTIFICATIONS_EMAIL_ENABLED=true no arquivo .env\n";
        exit(1);
    }
    
    // Verificar configurações de email
    $mailHost = $config->get('mail_host');
    $mailUsername = $config->get('mail_username');
    $mailPassword = $config->get('mail_password');
    
    if (empty($mailUsername) || empty($mailPassword)) {
        echo "❌ Configurações de email incompletas no .env\n";
        echo "Configure MAIL_USERNAME e MAIL_PASSWORD no arquivo .env\n";
        exit(1);
    }
    
    echo "✅ Configurações de email encontradas\n";
    echo "📧 Host: $mailHost\n";
    echo "👤 Usuário: $mailUsername\n";
    
    // Testar envio de email
    $to = $mailUsername; // Enviar para o próprio email configurado
    $subject = "Teste de Email - Sistema de Gerenciamento de Alunos";
    $message = "Este é um email de teste do sistema de gerenciamento de alunos.\n\n";
    $message .= "Data/Hora: " . date('Y-m-d H:i:s') . "\n";
    $message .= "Se você recebeu este email, a configuração está funcionando corretamente!\n\n";
    $message .= "Atenciosamente,\nSistema de Gerenciamento de Alunos";
    
    $result = $notificationService->sendEmail($to, $subject, $message);
    
    if ($result) {
        echo "✅ Email enviado com sucesso!\n";
        echo "📧 Verifique sua caixa de entrada: $to\n";
    } else {
        echo "❌ Erro ao enviar email\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
    echo "📋 Stack trace:\n" . $e->getTraceAsString() . "\n";
} 
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Alura\Pdo\Infrastructure\Service\EnvironmentConfig;

echo "🔧 Assistente de Configuração de Email\n";
echo "=====================================\n\n";

$config = EnvironmentConfig::getInstance();

echo "📋 Configurações atuais:\n";
echo "MAIL_HOST: " . $config->get('mail_host') . "\n";
echo "MAIL_PORT: " . $config->get('mail_port') . "\n";
echo "MAIL_USERNAME: " . ($config->get('mail_username') ?: 'NÃO CONFIGURADO') . "\n";
echo "MAIL_PASSWORD: " . ($config->get('mail_password') ? 'CONFIGURADO' : 'NÃO CONFIGURADO') . "\n";
echo "NOTIFICATIONS_EMAIL_ENABLED: " . ($config->get('notifications_email_enabled') ? 'SIM' : 'NÃO') . "\n\n";

echo "📝 Instruções para configurar Gmail:\n\n";

echo "1️⃣ ATIVAR VERIFICAÇÃO EM DUAS ETAPAS:\n";
echo "   - Acesse: https://myaccount.google.com/security\n";
echo "   - Ative 'Verificação em duas etapas'\n\n";

echo "2️⃣ GERAR SENHA DE APP:\n";
echo "   - Na mesma página, procure 'Senhas de app'\n";
echo "   - Clique em 'Senhas de app'\n";
echo "   - Selecione 'Email' ou 'Outro'\n";
echo "   - Digite nome: 'Sistema de Alunos'\n";
echo "   - Clique 'Gerar'\n";
echo "   - Copie a senha de 16 caracteres\n\n";

echo "3️⃣ CONFIGURAR ARQUIVO .env:\n";
echo "   MAIL_USERNAME=seu-email@gmail.com\n";
echo "   MAIL_PASSWORD=sua-senha-de-app-gerada\n";
echo "   NOTIFICATIONS_EMAIL_ENABLED=true\n\n";

echo "4️⃣ TESTAR:\n";
echo "   php scripts/teste-email.php\n\n";

echo "🔧 CONFIGURAÇÕES ALTERNATIVAS:\n\n";

echo "📧 OUTLOOK/HOTMAIL:\n";
echo "   MAIL_HOST=smtp-mail.outlook.com\n";
echo "   MAIL_PORT=587\n";
echo "   MAIL_ENCRYPTION=tls\n\n";

echo "📧 YAHOO:\n";
echo "   MAIL_HOST=smtp.mail.yahoo.com\n";
echo "   MAIL_PORT=587\n";
echo "   MAIL_ENCRYPTION=tls\n\n";

echo "⚠️  IMPORTANTE:\n";
echo "- Use SEMPRE senha de app (não senha normal)\n";
echo "- Verificação em duas etapas é obrigatória para senha de app\n";
echo "- Teste sempre antes de usar em produção\n\n";

echo "❓ PRECISA DE AJUDA?\n";
echo "- Verifique se a verificação em duas etapas está ativa\n";
echo "- Confirme se copiou a senha de app corretamente\n";
echo "- Teste a conexão com: php scripts/teste-email.php\n"; 
<?php

namespace Alura\Pdo\Infrastructure\Service;

use Alura\Pdo\Infrastructure\Service\EnvironmentConfig;

class NotificationService
{
    private static ?NotificationService $instance = null;
    private EnvironmentConfig $config;
    private AppLogger $logger;

    private function __construct()
    {
        $this->config = EnvironmentConfig::getInstance();
        $this->logger = AppLogger::getInstance();
    }

    public static function getInstance(): NotificationService
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Envia notificação por email
     */
    public function sendEmail(string $to, string $subject, string $message, array $options = []): bool
    {
        if (!$this->config->get('notifications_enabled')) {
            $this->logger->info('Notificações por email desabilitadas', [
                'to' => $to,
                'subject' => $subject
            ]);
            return false;
        }

        try {
            $headers = [
                'From: ' . ($options['from'] ?? $this->config->get('app_email', 'noreply@example.com')),
                'Reply-To: ' . ($options['reply_to'] ?? $this->config->get('app_email', 'noreply@example.com')),
                'Content-Type: text/html; charset=UTF-8',
                'X-Mailer: PHP/' . PHP_VERSION
            ];

            if (isset($options['cc'])) {
                $headers[] = 'Cc: ' . $options['cc'];
            }

            if (isset($options['bcc'])) {
                $headers[] = 'Bcc: ' . $options['bcc'];
            }

            $htmlMessage = $this->formatEmailMessage($message, $options);

            $result = mail($to, $subject, $htmlMessage, implode("\r\n", $headers));

            if ($result) {
                $this->logger->info('Email enviado com sucesso', [
                    'to' => $to,
                    'subject' => $subject
                ]);
            } else {
                $this->logger->error('Falha ao enviar email', [
                    'to' => $to,
                    'subject' => $subject
                ]);
            }

            return $result;
        } catch (\Exception $e) {
            $this->logger->error('Erro ao enviar email: ' . $e->getMessage(), [
                'to' => $to,
                'subject' => $subject
            ]);
            return false;
        }
    }

    /**
     * Envia notificação do navegador (Browser Notification)
     */
    public function sendBrowserNotification(string $title, string $message, array $options = []): bool
    {
        if (!$this->config->get('notifications_enabled')) {
            $this->logger->info('Notificações do navegador desabilitadas', [
                'title' => $title
            ]);
            return false;
        }

        try {
            $notification = [
                'title' => $title,
                'message' => $message,
                'icon' => $options['icon'] ?? '/public/assets/images/notification-icon.png',
                'badge' => $options['badge'] ?? '/public/assets/images/badge-icon.png',
                'tag' => $options['tag'] ?? 'default',
                'data' => $options['data'] ?? [],
                'requireInteraction' => $options['requireInteraction'] ?? false,
                'silent' => $options['silent'] ?? false
            ];

            $this->logger->info('Notificação do navegador enviada', [
                'title' => $title,
                'tag' => $notification['tag']
            ]);

            return true;
        } catch (\Exception $e) {
            $this->logger->error('Erro ao enviar notificação do navegador: ' . $e->getMessage(), [
                'title' => $title
            ]);
            return false;
        }
    }

    /**
     * Envia notificação de novo aluno cadastrado
     */
    public function notifyNewStudent(string $studentName, string $studentEmail = null): bool
    {
        $subject = '🎓 Novo Aluno Cadastrado - ' . $this->config->get('app_name');
        $message = "
            <h2>🎉 Novo Aluno Cadastrado!</h2>
            <p><strong>Nome:</strong> {$studentName}</p>
            <p><strong>Data/Hora:</strong> " . date('d/m/Y H:i:s') . "</p>
            <p><strong>Sistema:</strong> " . $this->config->get('app_name') . "</p>
        ";

        $adminEmail = $this->config->get('admin_email');
        if ($adminEmail) {
            $this->sendEmail($adminEmail, $subject, $message);
        }

        $this->sendBrowserNotification(
            'Novo Aluno Cadastrado',
            "O aluno {$studentName} foi cadastrado no sistema."
        );

        return true;
    }

    /**
     * Envia notificação de exclusão de aluno
     */
    public function notifyStudentDeleted(string $studentName): bool
    {
        $subject = '🗑️ Aluno Excluído - ' . $this->config->get('app_name');
        $message = "
            <h2>⚠️ Aluno Excluído</h2>
            <p><strong>Nome:</strong> {$studentName}</p>
            <p><strong>Data/Hora:</strong> " . date('d/m/Y H:i:s') . "</p>
            <p><strong>Sistema:</strong> " . $this->config->get('app_name') . "</p>
        ";

        $adminEmail = $this->config->get('admin_email');
        if ($adminEmail) {
            $this->sendEmail($adminEmail, $subject, $message);
        }

        $this->sendBrowserNotification(
            'Aluno Excluído',
            "O aluno {$studentName} foi excluído do sistema."
        );

        return true;
    }

    /**
     * Envia notificação de erro do sistema
     */
    public function notifySystemError(string $error, string $context = ''): bool
    {
        $subject = '🚨 Erro no Sistema - ' . $this->config->get('app_name');
        $message = "
            <h2>🚨 Erro Detectado</h2>
            <p><strong>Erro:</strong> {$error}</p>
            <p><strong>Contexto:</strong> {$context}</p>
            <p><strong>Data/Hora:</strong> " . date('d/m/Y H:i:s') . "</p>
            <p><strong>Sistema:</strong> " . $this->config->get('app_name') . "</p>
        ";

        $adminEmail = $this->config->get('admin_email');
        if ($adminEmail) {
            $this->sendEmail($adminEmail, $subject, $message);
        }

        $this->sendBrowserNotification(
            'Erro no Sistema',
            "Um erro foi detectado: {$error}"
        );

        return true;
    }

    /**
     * Formata mensagem de email em HTML
     */
    private function formatEmailMessage(string $message, array $options = []): string
    {
        $template = $options['template'] ?? 'default';
        
        switch ($template) {
            case 'minimal':
                return "
                    <html>
                    <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                        {$message}
                    </body>
                    </html>
                ";
            
            default:
                return "
                    <html>
                    <head>
                        <style>
                            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; }
                            .container { max-width: 600px; margin: 0 auto; background: #f9f9f9; padding: 30px; border-radius: 10px; }
                            .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
                            .content { background: white; padding: 20px; border-radius: 8px; }
                            .footer { text-align: center; margin-top: 20px; color: #666; font-size: 0.9em; }
                        </style>
                    </head>
                    <body>
                        <div class='container'>
                            <div class='header'>
                                <h1 style='margin: 0;'>" . $this->config->get('app_name') . "</h1>
                            </div>
                            <div class='content'>
                                {$message}
                            </div>
                            <div class='footer'>
                                <p>Este é um email automático do sistema " . $this->config->get('app_name') . "</p>
                                <p>Data: " . date('d/m/Y H:i:s') . "</p>
                            </div>
                        </div>
                    </body>
                    </html>
                ";
        }
    }

    /**
     * Testa o serviço de notificações
     */
    public function testNotifications(): array
    {
        $results = [];

        // Teste de email
        $testEmail = $this->config->get('admin_email');
        if ($testEmail) {
            $results['email'] = $this->sendEmail(
                $testEmail,
                '🧪 Teste de Notificação - ' . $this->config->get('app_name'),
                '<h2>Teste de Email</h2><p>Esta é uma mensagem de teste do sistema de notificações.</p>'
            );
        } else {
            $results['email'] = false;
        }

        // Teste de notificação do navegador
        $results['browser'] = $this->sendBrowserNotification(
            'Teste de Notificação',
            'Esta é uma notificação de teste do sistema.'
        );

        return $results;
    }
} 
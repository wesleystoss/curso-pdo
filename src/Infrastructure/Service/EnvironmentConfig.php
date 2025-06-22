<?php

namespace Alura\Pdo\Infrastructure\Service;

use Dotenv\Dotenv;

class EnvironmentConfig
{
    private static ?self $instance = null;
    private array $config = [];
    private bool $loaded = false;

    private function __construct()
    {
        $this->loadEnvironment();
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function loadEnvironment(): void
    {
        if ($this->loaded) {
            return;
        }

        $envFile = dirname(__DIR__, 3) . '/.env';
        
        if (file_exists($envFile)) {
            $dotenv = Dotenv::createImmutable(dirname(__DIR__, 3));
            $dotenv->load();
        }

        $this->config = [
            // Database
            'db_connection' => $_ENV['DB_CONNECTION'] ?? 'sqlite',
            'db_database' => $_ENV['DB_DATABASE'] ?? 'database/banco.sqlite',
            'db_host' => $_ENV['DB_HOST'] ?? 'localhost',
            'db_port' => $_ENV['DB_PORT'] ?? '3306',
            'db_username' => $_ENV['DB_USERNAME'] ?? '',
            'db_password' => $_ENV['DB_PASSWORD'] ?? '',
            
            // Application
            'app_name' => $_ENV['APP_NAME'] ?? 'Sistema de Gerenciamento de Alunos',
            'app_env' => $_ENV['APP_ENV'] ?? 'production',
            'app_debug' => filter_var($_ENV['APP_DEBUG'] ?? 'false', FILTER_VALIDATE_BOOLEAN),
            'app_url' => $_ENV['APP_URL'] ?? 'http://localhost:8000',
            'app_timezone' => $_ENV['APP_TIMEZONE'] ?? 'America/Sao_Paulo',
            'app_locale' => $_ENV['APP_LOCALE'] ?? 'pt_BR',
            'app_key' => $_ENV['APP_KEY'] ?? 'base64:default-key-change-in-production',
            
            // Security
            'session_secure' => filter_var($_ENV['SESSION_SECURE'] ?? 'false', FILTER_VALIDATE_BOOLEAN),
            'session_http_only' => filter_var($_ENV['SESSION_HTTP_ONLY'] ?? 'true', FILTER_VALIDATE_BOOLEAN),
            'session_same_site' => $_ENV['SESSION_SAME_SITE'] ?? 'lax',
            
            // Cache
            'cache_driver' => $_ENV['CACHE_DRIVER'] ?? 'file',
            'cache_enabled' => filter_var($_ENV['CACHE_ENABLED'] ?? 'true', FILTER_VALIDATE_BOOLEAN),
            'cache_ttl' => (int)($_ENV['CACHE_TTL'] ?? 3600),
            
            // Log
            'log_channel' => $_ENV['LOG_CHANNEL'] ?? 'stack',
            'log_level' => $_ENV['LOG_LEVEL'] ?? 'info',
            'log_max_files' => (int)($_ENV['LOG_MAX_FILES'] ?? 30),
            
            // Email
            'mail_mailer' => $_ENV['MAIL_MAILER'] ?? 'smtp',
            'mail_host' => $_ENV['MAIL_HOST'] ?? 'smtp.gmail.com',
            'mail_port' => (int)($_ENV['MAIL_PORT'] ?? 587),
            'mail_username' => $_ENV['MAIL_USERNAME'] ?? '',
            'mail_password' => $_ENV['MAIL_PASSWORD'] ?? '',
            'mail_encryption' => $_ENV['MAIL_ENCRYPTION'] ?? 'tls',
            'mail_from_address' => $_ENV['MAIL_FROM_ADDRESS'] ?? 'noreply@example.com',
            'mail_from_name' => $_ENV['MAIL_FROM_NAME'] ?? $this->get('app_name'),
            
            // API
            'api_rate_limit' => (int)($_ENV['API_RATE_LIMIT'] ?? 60),
            'api_rate_limit_window' => (int)($_ENV['API_RATE_LIMIT_WINDOW'] ?? 60),
            'api_version' => $_ENV['API_VERSION'] ?? 'v1',
            
            // External Services
            'cep_service_url' => $_ENV['CEP_SERVICE_URL'] ?? 'https://viacep.com.br/ws',
            'cep_service_timeout' => (int)($_ENV['CEP_SERVICE_TIMEOUT'] ?? 10),
            
            // Export
            'export_csv_enabled' => filter_var($_ENV['EXPORT_CSV_ENABLED'] ?? 'true', FILTER_VALIDATE_BOOLEAN),
            'export_excel_enabled' => filter_var($_ENV['EXPORT_EXCEL_ENABLED'] ?? 'true', FILTER_VALIDATE_BOOLEAN),
            'export_pdf_enabled' => filter_var($_ENV['EXPORT_PDF_ENABLED'] ?? 'true', FILTER_VALIDATE_BOOLEAN),
            
            // Pagination
            'pagination_items_per_page' => (int)($_ENV['PAGINATION_ITEMS_PER_PAGE'] ?? 10),
            'pagination_max_items_per_page' => (int)($_ENV['PAGINATION_MAX_ITEMS_PER_PAGE'] ?? 100),
            
            // Notifications
            'notifications_enabled' => filter_var($_ENV['NOTIFICATIONS_ENABLED'] ?? 'true', FILTER_VALIDATE_BOOLEAN),
            'notifications_email_enabled' => filter_var($_ENV['NOTIFICATIONS_EMAIL_ENABLED'] ?? 'false', FILTER_VALIDATE_BOOLEAN),
            'notifications_browser_enabled' => filter_var($_ENV['NOTIFICATIONS_BROWSER_ENABLED'] ?? 'true', FILTER_VALIDATE_BOOLEAN),
        ];

        $this->loaded = true;
    }

    public function get(string $key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }

    public function set(string $key, $value): void
    {
        $this->config[$key] = $value;
    }

    public function toArray(): array
    {
        return $this->config;
    }

    public function isProduction(): bool
    {
        return $this->get('app_env') === 'production';
    }

    public function isDevelopment(): bool
    {
        return $this->get('app_env') === 'development';
    }

    public function isTesting(): bool
    {
        return $this->get('app_env') === 'testing';
    }

    public function isDebugEnabled(): bool
    {
        return $this->get('app_debug', false);
    }

    public function getDatabasePath(): string
    {
        $path = $this->get('db_database');
        if (!file_exists($path)) {
            $dir = dirname($path);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
        }
        return $path;
    }

    public function getLogPath(): string
    {
        $logDir = dirname(__DIR__, 3) . '/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        return $logDir;
    }

    public function getCachePath(): string
    {
        $cacheDir = dirname(__DIR__, 3) . '/cache';
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }
        return $cacheDir;
    }

    /**
     * @return array<string>
     */
    public function validate(): array
    {
        $errors = [];

        // Validar configurações obrigatórias
        if (empty($this->get('app_name'))) {
            $errors[] = 'APP_NAME é obrigatório';
        }

        if (empty($this->get('db_database'))) {
            $errors[] = 'DB_DATABASE é obrigatório';
        }

        // Validar configurações de email se habilitado
        if ($this->get('notifications_email_enabled') && empty($this->get('mail_host'))) {
            $errors[] = 'MAIL_HOST é obrigatório quando notificações por email estão habilitadas';
        }

        return $errors;
    }

    public function reload(): void
    {
        $this->loaded = false;
        $this->loadEnvironment();
    }
} 
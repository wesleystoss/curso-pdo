<?php

namespace Alura\Pdo\Infrastructure\Service;

class EnvironmentConfig
{
    private static ?EnvironmentConfig $instance = null;
    private array $config = [];

    private function __construct()
    {
        $this->loadEnvironment();
        $this->setDefaults();
    }

    public static function getInstance(): EnvironmentConfig
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function loadEnvironment(): void
    {
        $envFile = __DIR__ . '/../../../.env';
        
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            
            foreach ($lines as $line) {
                if (strpos($line, '#') === 0) {
                    continue; // Comentário
                }
                
                if (strpos($line, '=') !== false) {
                    [$key, $value] = explode('=', $line, 2);
                    $key = trim($key);
                    $value = trim($value);
                    
                    // Remove aspas se existirem
                    if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
                        (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
                        $value = substr($value, 1, -1);
                    }
                    
                    $this->config[$key] = $value;
                }
            }
        }
    }

    private function setDefaults(): void
    {
        $defaults = [
            'APP_ENV' => 'development',
            'APP_DEBUG' => 'true',
            'APP_NAME' => 'Sistema de Gerenciamento de Alunos',
            'APP_VERSION' => '2.0.0',
            'DB_PATH' => __DIR__ . '/../../../database/banco.sqlite',
            'DB_TEST_PATH' => __DIR__ . '/../../../database/banco-teste.sqlite',
            'CACHE_TTL' => '3600',
            'LOG_LEVEL' => 'INFO',
            'API_RATE_LIMIT' => '100',
            'CORS_ALLOW_ORIGIN' => '*',
            'PAGINATION_LIMIT' => '10'
        ];

        foreach ($defaults as $key => $value) {
            if (!isset($this->config[$key])) {
                $this->config[$key] = $value;
            }
        }
    }

    public function get(string $key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }

    public function set(string $key, $value): void
    {
        $this->config[$key] = $value;
    }

    public function isDevelopment(): bool
    {
        return $this->get('APP_ENV') === 'development';
    }

    public function isProduction(): bool
    {
        return $this->get('APP_ENV') === 'production';
    }

    public function isTesting(): bool
    {
        return $this->get('APP_ENV') === 'testing';
    }

    public function isDebugEnabled(): bool
    {
        return $this->get('APP_DEBUG') === 'true';
    }

    public function getDatabasePath(): string
    {
        return $this->isTesting() 
            ? $this->get('DB_TEST_PATH') 
            : $this->get('DB_PATH');
    }

    public function getCacheTtl(): int
    {
        return (int) $this->get('CACHE_TTL', 3600);
    }

    public function getLogLevel(): string
    {
        return $this->get('LOG_LEVEL', 'INFO');
    }

    public function getApiRateLimit(): int
    {
        return (int) $this->get('API_RATE_LIMIT', 100);
    }

    public function getCorsAllowOrigin(): string
    {
        return $this->get('CORS_ALLOW_ORIGIN', '*');
    }

    public function getPaginationLimit(): int
    {
        return (int) $this->get('PAGINATION_LIMIT', 10);
    }

    public function getAll(): array
    {
        return $this->config;
    }

    public function toArray(): array
    {
        return [
            'app' => [
                'name' => $this->get('APP_NAME'),
                'version' => $this->get('APP_VERSION'),
                'environment' => $this->get('APP_ENV'),
                'debug' => $this->isDebugEnabled()
            ],
            'database' => [
                'path' => $this->getDatabasePath()
            ],
            'cache' => [
                'ttl' => $this->getCacheTtl()
            ],
            'logging' => [
                'level' => $this->getLogLevel()
            ],
            'api' => [
                'rate_limit' => $this->getApiRateLimit(),
                'cors_allow_origin' => $this->getCorsAllowOrigin()
            ],
            'pagination' => [
                'limit' => $this->getPaginationLimit()
            ]
        ];
    }
} 
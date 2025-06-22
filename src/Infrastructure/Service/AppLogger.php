<?php

namespace Alura\Pdo\Infrastructure\Service;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;

class AppLogger
{
    private static ?self $instance = null;
    private Logger $monologLogger;
    private EnvironmentConfig $config;

    private function __construct()
    {
        $this->config = EnvironmentConfig::getInstance();
        $this->initializeLogger();
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function initializeLogger(): void
    {
        $this->monologLogger = new Logger('student-management');
        
        // Configurar timezone
        $this->monologLogger->setTimezone(new \DateTimeZone($this->config->get('app_timezone')));
        
        // Handler para arquivo de log rotativo
        $logPath = $this->config->getLogPath() . '/app.log';
        $maxFiles = $this->config->get('log_max_files', 30);
        
        $rotatingHandler = new RotatingFileHandler($logPath, $maxFiles, Logger::DEBUG);
        
        // Formato personalizado
        $formatter = new LineFormatter(
            "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n",
            'Y-m-d H:i:s',
            true,
            true
        );
        
        $rotatingHandler->setFormatter($formatter);
        $this->monologLogger->pushHandler($rotatingHandler);
        
        // Handler para erros (arquivo separado)
        $errorLogPath = $this->config->getLogPath() . '/error.log';
        $errorHandler = new RotatingFileHandler($errorLogPath, $maxFiles, Logger::ERROR);
        $errorHandler->setFormatter($formatter);
        $this->monologLogger->pushHandler($errorHandler);
        
        // Em desenvolvimento, também logar no console
        if ($this->config->isDevelopment() || $this->config->isDebugEnabled()) {
            $consoleHandler = new StreamHandler('php://stdout', Logger::DEBUG);
            $consoleHandler->setFormatter($formatter);
            $this->monologLogger->pushHandler($consoleHandler);
        }
    }

    /**
     * @param array<string, mixed> $context
     */
    public function emergency(string $message, array $context = []): void
    {
        $this->monologLogger->emergency($message, $context);
    }

    /**
     * @param array<string, mixed> $context
     */
    public function alert(string $message, array $context = []): void
    {
        $this->monologLogger->alert($message, $context);
    }

    /**
     * @param array<string, mixed> $context
     */
    public function critical(string $message, array $context = []): void
    {
        $this->monologLogger->critical($message, $context);
    }

    /**
     * @param array<string, mixed> $context
     */
    public function error(string $message, array $context = []): void
    {
        $this->monologLogger->error($message, $context);
    }

    /**
     * @param array<string, mixed> $context
     */
    public function warning(string $message, array $context = []): void
    {
        $this->monologLogger->warning($message, $context);
    }

    /**
     * @param array<string, mixed> $context
     */
    public function notice(string $message, array $context = []): void
    {
        $this->monologLogger->notice($message, $context);
    }

    /**
     * @param array<string, mixed> $context
     */
    public function info(string $message, array $context = []): void
    {
        $this->monologLogger->info($message, $context);
    }

    /**
     * @param array<string, mixed> $context
     */
    public function debug(string $message, array $context = []): void
    {
        $this->monologLogger->debug($message, $context);
    }

    /**
     * @param array<string, mixed> $context
     */
    public function log(string $level, string $message, array $context = []): void
    {
        $this->monologLogger->log($level, $message, $context);
    }

    // Métodos específicos para o domínio
    public function logStudentCreated(int $studentId, string $studentName): void
    {
        $this->info('Aluno criado', [
            'student_id' => $studentId,
            'student_name' => $studentName,
            'action' => 'create',
            'user_ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);
    }

    public function logStudentUpdated(int $studentId, string $studentName): void
    {
        $this->info('Aluno atualizado', [
            'student_id' => $studentId,
            'student_name' => $studentName,
            'action' => 'update',
            'user_ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);
    }

    public function logStudentDeleted(int $studentId, string $studentName): void
    {
        $this->warning('Aluno excluído', [
            'student_id' => $studentId,
            'student_name' => $studentName,
            'action' => 'delete',
            'user_ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);
    }

    /**
     * @param array<int> $studentIds
     */
    public function logBulkDelete(array $studentIds): void
    {
        $this->warning('Exclusão em lote realizada', [
            'student_ids' => $studentIds,
            'count' => count($studentIds),
            'action' => 'bulk_delete',
            'user_ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);
    }

    public function logSearch(string $criteria, int $resultsCount): void
    {
        $this->debug('Busca realizada', [
            'criteria' => $criteria,
            'results_count' => $resultsCount,
            'action' => 'search',
            'user_ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);
    }

    public function logCepRequest(string $cep, bool $success, ?string $error = null): void
    {
        $context = [
            'cep' => $cep,
            'success' => $success,
            'action' => 'cep_request',
            'user_ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ];

        if ($error) {
            $context['error'] = $error;
        }

        $level = $success ? 'debug' : 'warning';
        $this->$level('Requisição de CEP', $context);
    }

    public function logApiRequest(string $method, string $endpoint, int $statusCode, float $duration): void
    {
        $this->info('Requisição API', [
            'method' => $method,
            'endpoint' => $endpoint,
            'status_code' => $statusCode,
            'duration_ms' => round($duration * 1000, 2),
            'action' => 'api_request',
            'user_ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);
    }

    /**
     * @param array<string, mixed> $context
     */
    public function logException(\Throwable $exception, array $context = []): void
    {
        $this->error('Exceção capturada', [
            'exception' => get_class($exception),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'context' => $context
        ]);
    }

    /**
     * @return array<int, string>
     */
    public function getRecentLogs(int $limit = 50): array
    {
        $logFile = $this->config->getLogPath() . '/app.log';
        
        if (!file_exists($logFile)) {
            return [];
        }

        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (!$lines) {
            return [];
        }

        // Pegar as últimas linhas
        $recentLines = array_slice($lines, -$limit);
        
        return array_reverse($recentLines);
    }

    public function clearLogs(): void
    {
        $logPath = $this->config->getLogPath();
        $files = glob($logPath . '/*.log');
        
        foreach ($files as $file) {
            if (is_file($file)) {
                file_put_contents($file, '');
            }
        }
        
        $this->info('Logs limpos manualmente');
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function getLogStats(): array
    {
        $logPath = $this->config->getLogPath();
        $stats = [];
        
        $files = glob($logPath . '/*.log');
        foreach ($files as $file) {
            $filename = basename($file);
            $stats[$filename] = [
                'size' => filesize($file),
                'size_formatted' => $this->formatBytes(filesize($file)),
                'lines' => count(file($file)),
                'last_modified' => date('Y-m-d H:i:s', filemtime($file))
            ];
        }
        
        return $stats;
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
} 
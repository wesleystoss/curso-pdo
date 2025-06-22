<?php

namespace Alura\Pdo\Infrastructure\Service;

class Cache
{
    private string $cacheDir;
    private static ?Cache $instance = null;

    private function __construct()
    {
        $this->cacheDir = __DIR__ . '/../../../cache/';
        $this->ensureCacheDirectoryExists();
    }

    public static function getInstance(): Cache
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function ensureCacheDirectoryExists(): void
    {
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
    }

    public function set(string $key, $value, int $ttl = 3600): bool
    {
        $filename = $this->getCacheFilename($key);
        $data = [
            'value' => $value,
            'expires_at' => time() + $ttl,
            'created_at' => time()
        ];

        return file_put_contents($filename, serialize($data), LOCK_EX) !== false;
    }

    public function get(string $key)
    {
        $filename = $this->getCacheFilename($key);
        
        if (!file_exists($filename)) {
            return null;
        }

        $data = unserialize(file_get_contents($filename));
        
        if (!is_array($data) || !isset($data['expires_at']) || !isset($data['value'])) {
            $this->delete($key);
            return null;
        }

        if (time() > $data['expires_at']) {
            $this->delete($key);
            return null;
        }

        return $data['value'];
    }

    public function delete(string $key): bool
    {
        $filename = $this->getCacheFilename($key);
        if (file_exists($filename)) {
            return unlink($filename);
        }
        return true;
    }

    public function has(string $key): bool
    {
        return $this->get($key) !== null;
    }

    public function clear(): bool
    {
        $files = glob($this->cacheDir . '*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        return true;
    }

    public function clearExpired(): int
    {
        $files = glob($this->cacheDir . '*');
        $deleted = 0;

        foreach ($files as $file) {
            if (is_file($file)) {
                $data = unserialize(file_get_contents($file));
                if (is_array($data) && isset($data['expires_at']) && time() > $data['expires_at']) {
                    unlink($file);
                    $deleted++;
                }
            }
        }

        return $deleted;
    }

    private function getCacheFilename(string $key): string
    {
        return $this->cacheDir . md5($key) . '.cache';
    }

    /**
     * @return array{
     *     total_files: int,
     *     total_size: int,
     *     expired_files: int,
     *     valid_files: int
     * }
     */
    public function getStats(): array
    {
        $files = glob($this->cacheDir . '*');
        $totalFiles = count($files);
        $totalSize = 0;
        $expiredFiles = 0;

        foreach ($files as $file) {
            if (is_file($file)) {
                $totalSize += filesize($file);
                $data = unserialize(file_get_contents($file));
                if (is_array($data) && isset($data['expires_at']) && time() > $data['expires_at']) {
                    $expiredFiles++;
                }
            }
        }

        return [
            'total_files' => $totalFiles,
            'total_size' => $totalSize,
            'expired_files' => $expiredFiles,
            'valid_files' => $totalFiles - $expiredFiles
        ];
    }
} 
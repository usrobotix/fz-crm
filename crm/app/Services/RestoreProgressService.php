<?php

namespace App\Services;

class RestoreProgressService
{
    private string $dir;

    public function __construct()
    {
        $this->dir = storage_path('app/restore-progress');
    }

    public function write(string $uuid, array $data): void
    {
        $this->ensureDir();
        file_put_contents($this->resolvedPath($uuid), json_encode($data, JSON_UNESCAPED_UNICODE), LOCK_EX);
    }

    public function read(string $uuid): ?array
    {
        $path = $this->resolvedPath($uuid);
        if (!is_file($path)) return null;
        $json = file_get_contents($path);
        if ($json === false) return null;
        $data = json_decode($json, true);
        return is_array($data) ? $data : null;
    }

    public function delete(string $uuid): void
    {
        $path = $this->resolvedPath($uuid);
        if (is_file($path)) @unlink($path);
    }

    public function purgeOld(int $maxAgeHours = 48): void
    {
        if (!is_dir($this->dir)) return;
        $cutoff = time() - ($maxAgeHours * 3600);
        foreach (glob($this->dir . DIRECTORY_SEPARATOR . '*.json') as $file) {
            if (filemtime($file) < $cutoff) @unlink($file);
        }
    }

    public function resolvedPath(string $uuid): string
    {
        if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $uuid)) {
            throw new \InvalidArgumentException('Invalid restore UUID format.');
        }
        return $this->dir . DIRECTORY_SEPARATOR . strtolower($uuid) . '.json';
    }

    private function ensureDir(): void
    {
        if (!is_dir($this->dir)) mkdir($this->dir, 0755, true);
    }
}

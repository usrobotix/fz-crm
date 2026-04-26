<?php

namespace App\Jobs;

use App\Models\Backup;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RunBackupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $backupId) {}

    public function handle(): void
    {
        $backup = Backup::find($this->backupId);
        if (!$backup) {
            Log::error('[RunBackupJob] Backup record not found', ['backup_id' => $this->backupId]);
            return;
        }
        try {
            $backup->update(['status' => 'running', 'started_at' => now(), 'current_step' => 'dumping']);

            $dir = storage_path('app/backups');
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            $filename = 'backup_db_' . now()->format('Y-m-d_His') . '.sql';
            $zipFilename = $filename . '.zip';
            $sqlPath = $dir . DIRECTORY_SEPARATOR . $filename;
            $zipPath = $dir . DIRECTORY_SEPARATOR . $zipFilename;

            $dbHost = config('database.connections.mysql.host', '127.0.0.1');
            $dbPort = config('database.connections.mysql.port', '3306');
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbPass = config('database.connections.mysql.password');

            $cmd = sprintf(
                'mysqldump --host=%s --port=%s --user=%s --password=%s %s > %s 2>&1',
                escapeshellarg($dbHost),
                escapeshellarg($dbPort),
                escapeshellarg($dbUser),
                escapeshellarg($dbPass),
                escapeshellarg($dbName),
                escapeshellarg($sqlPath)
            );
            exec($cmd, $output, $retval);

            if ($retval !== 0) throw new \RuntimeException('mysqldump failed: ' . implode("\n", $output));

            $backup->update(['current_step' => 'compressing', 'progress_percent' => 60]);

            $zip = new \ZipArchive();
            $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
            $zip->addFile($sqlPath, $filename);
            $zip->close();
            @unlink($sqlPath);

            $sizeBytes = is_file($zipPath) ? filesize($zipPath) : 0;
            $backup->update([
                'status' => 'done',
                'current_step' => 'done',
                'progress_percent' => 100,
                'local_paths' => ['zip' => $zipPath],
                'size_bytes' => $sizeBytes,
                'finished_at' => now(),
            ]);

            Log::info('[RunBackupJob] Backup completed', ['backup_id' => $backup->id, 'path' => $zipPath]);
        } catch (\Throwable $e) {
            Log::error('[RunBackupJob] Exception', ['backup_id' => $this->backupId, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            if (isset($backup)) {
                $backup->update(['status' => 'failed', 'error_message' => $e->getMessage(), 'finished_at' => now()]);
            }
        }
    }

    public function failed(\Throwable $e): void
    {
        Log::error('[RunBackupJob] Job failed', ['backup_id' => $this->backupId, 'error' => $e->getMessage()]);
        Backup::where('id', $this->backupId)->update(['status' => 'failed', 'error_message' => $e->getMessage(), 'finished_at' => now()]);
    }
}

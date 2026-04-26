<?php

namespace App\Jobs;

use App\Models\Backup;
use App\Services\RestoreProgressService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RestoreDatabaseFromBackupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $sourceBackupId,
        public int $restoreRecordId,
        public string $restoreUuid,
    ) {}

    public function handle(): void
    {
        $progress = new RestoreProgressService();
        $sourceBackup = Backup::find($this->sourceBackupId);
        $restoreRecord = Backup::find($this->restoreRecordId);

        try {
            $progress->write($this->restoreUuid, ['status' => 'running', 'progress_percent' => 5, 'current_step' => 'starting', 'error_message' => null, 'finished_at' => null]);
            if ($restoreRecord) $restoreRecord->update(['status' => 'running', 'started_at' => now(), 'current_step' => 'starting']);

            if (!$sourceBackup) throw new \RuntimeException("Source backup #{$this->sourceBackupId} not found.");

            $paths = $sourceBackup->local_paths ?? [];
            $zipPath = $paths['zip'] ?? null;
            if (!$zipPath || !is_file($zipPath)) throw new \RuntimeException("Backup file not found: $zipPath");

            $progress->write($this->restoreUuid, ['status' => 'running', 'progress_percent' => 20, 'current_step' => 'extracting', 'error_message' => null, 'finished_at' => null]);

            $tmpDir = sys_get_temp_dir() . '/fzcrm_restore_' . $this->restoreUuid;
            mkdir($tmpDir, 0755, true);

            $zip = new \ZipArchive();
            if ($zip->open($zipPath) !== true) throw new \RuntimeException("Cannot open ZIP: $zipPath");
            $zip->extractTo($tmpDir);
            $zip->close();

            $sqlFile = glob($tmpDir . '/*.sql')[0] ?? null;
            if (!$sqlFile) throw new \RuntimeException('No .sql file found in backup archive.');

            $progress->write($this->restoreUuid, ['status' => 'running', 'progress_percent' => 50, 'current_step' => 'restoring_db', 'error_message' => null, 'finished_at' => null]);

            $dbHost = config('database.connections.mysql.host', '127.0.0.1');
            $dbPort = config('database.connections.mysql.port', '3306');
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbPass = config('database.connections.mysql.password');

            $cmd = sprintf(
                'mysql --host=%s --port=%s --user=%s --password=%s %s < %s 2>&1',
                escapeshellarg($dbHost), escapeshellarg($dbPort),
                escapeshellarg($dbUser), escapeshellarg($dbPass),
                escapeshellarg($dbName), escapeshellarg($sqlFile)
            );
            exec($cmd, $output, $retval);

            array_map('unlink', glob($tmpDir . '/*'));
            rmdir($tmpDir);

            if ($retval !== 0) throw new \RuntimeException('mysql restore failed: ' . implode("\n", $output));

            $progress->write($this->restoreUuid, ['status' => 'done', 'progress_percent' => 100, 'current_step' => 'done', 'error_message' => null, 'finished_at' => now()->toIso8601String()]);

            Log::info('[RestoreDatabaseFromBackupJob] Restore completed', ['restore_uuid' => $this->restoreUuid]);
        } catch (\Throwable $e) {
            Log::error('[RestoreDatabaseFromBackupJob] Exception', ['restore_uuid' => $this->restoreUuid, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            $progress->write($this->restoreUuid, ['status' => 'failed', 'progress_percent' => 0, 'current_step' => 'failed', 'error_message' => $e->getMessage(), 'finished_at' => now()->toIso8601String()]);
            if ($restoreRecord) {
                $restoreRecord->update(['status' => 'failed', 'error_message' => $e->getMessage(), 'finished_at' => now()]);
            }
        }
    }

    public function failed(\Throwable $e): void
    {
        Log::error('[RestoreDatabaseFromBackupJob] Job failed', ['restore_uuid' => $this->restoreUuid, 'error' => $e->getMessage()]);
        $progress = new RestoreProgressService();
        $progress->write($this->restoreUuid, ['status' => 'failed', 'progress_percent' => 0, 'current_step' => 'failed', 'error_message' => $e->getMessage(), 'finished_at' => now()->toIso8601String()]);
    }
}

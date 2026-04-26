<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\RestoreDatabaseFromBackupJob;
use App\Jobs\RunBackupJob;
use App\Models\AuditEvent;
use App\Models\Backup;
use App\Services\RestoreProgressService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BackupController extends Controller
{
    public function index()
    {
        $backups = Backup::with('user')->orderBy('created_at', 'desc')->paginate(20);
        $queueIsSync = config('queue.default') === 'sync';
        return view('admin.technical.backups.index', compact('backups', 'queueIsSync'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type'      => 'required|in:db,files,full',
            'formats'   => 'required|array|min:1',
            'formats.*' => 'in:zip,tar_gz',
        ]);

        $backup = Backup::create([
            'type' => $data['type'],
            'kind' => 'backup',
            'formats' => $data['formats'],
            'size_bytes' => 0,
            'status' => 'queued',
            'initiated_by' => 'user',
            'user_id' => auth()->id(),
            'progress_percent' => 0,
            'current_step' => 'queued',
        ]);

        AuditEvent::log('backup.queued', ['type' => $data['type'], 'formats' => $data['formats']], 'backup', $backup->id);
        RunBackupJob::dispatch($backup->id);

        return redirect()->route('admin.technical.backups.index')
            ->with('success', 'Резервная копия поставлена в очередь.');
    }

    public function status(Backup $backup)
    {
        return response()->json([
            'id' => $backup->id,
            'status' => $backup->status,
            'progress_percent' => $backup->progress_percent,
            'current_step' => $backup->current_step,
            'error_message' => $backup->error_message,
            'finished_at' => $backup->finished_at?->toIso8601String(),
        ]);
    }

    public function download(Request $request, Backup $backup)
    {
        $fmt = $request->query('fmt', 'zip');
        $paths = $backup->local_paths ?? [];
        $path = $paths[$fmt] ?? $paths[array_key_first($paths)] ?? null;
        if (!$path || !is_file($path)) abort(404, 'Файл не найден');
        AuditEvent::log('backup.downloaded', ['backup_id' => $backup->id, 'fmt' => $fmt], 'backup', $backup->id);
        return response()->download($path);
    }

    public function destroy(Backup $backup)
    {
        $paths = $backup->local_paths ?? [];
        foreach ($paths as $path) {
            if (is_file($path)) @unlink($path);
        }
        AuditEvent::log('backup.deleted', ['backup_id' => $backup->id], 'backup', $backup->id);
        $backup->delete();
        return redirect()->route('admin.technical.backups.index')->with('success', 'Резервная копия удалена.');
    }

    public function restoreStatus(string $restoreUuid): \Illuminate\Http\JsonResponse
    {
        $progress = new RestoreProgressService();
        try {
            $data = $progress->read($restoreUuid);
        } catch (\InvalidArgumentException) {
            abort(400, 'Invalid restore UUID.');
        }
        if ($data === null) {
            return response()->json(['status' => 'gone', 'progress_percent' => 0], 410);
        }
        return response()->json($data);
    }

    public function restore(Request $request, Backup $backup)
    {
        if ($backup->status !== 'done' || !in_array($backup->type, ['db', 'full']) || ($backup->kind ?? 'backup') !== 'backup') {
            return redirect()->route('admin.technical.backups.index')
                ->with('error', 'Восстановление возможно только для успешно завершённых резервных копий БД.');
        }
        if ($request->input('confirmed') !== '1') {
            return redirect()->route('admin.technical.backups.index')->with('error', 'Необходимо подтвердить восстановление.');
        }

        $restoreRecord = Backup::create([
            'type' => 'db', 'kind' => 'restore', 'formats' => ['zip'],
            'status' => 'queued', 'initiated_by' => 'user',
            'user_id' => auth()->id(), 'progress_percent' => 0,
            'current_step' => 'queued', 'size_bytes' => 0,
        ]);

        $restoreUuid = (string) Str::uuid();
        $progress = new RestoreProgressService();
        $progress->write($restoreUuid, [
            'status' => 'queued', 'progress_percent' => 0, 'current_step' => 'queued',
            'error_message' => null, 'finished_at' => null, 'restore_record_id' => $restoreRecord->id,
        ]);

        AuditEvent::log('backup.restore_queued', ['source_backup_id' => $backup->id, 'restore_record_id' => $restoreRecord->id], 'backup', $backup->id);
        RestoreDatabaseFromBackupJob::dispatch($backup->id, $restoreRecord->id, $restoreUuid);

        return redirect()->route('admin.technical.backups.index')
            ->with('success', 'Восстановление БД поставлено в очередь.')
            ->with('restore_uuid', $restoreUuid);
    }
}

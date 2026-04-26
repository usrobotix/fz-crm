<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{
    protected $fillable = [
        'type', 'kind', 'file_preset', 'formats', 'local_paths', 'remote_paths',
        'size_bytes', 'status', 'error_message', 'initiated_by', 'user_id',
        'progress_percent', 'current_step', 'started_at', 'finished_at',
    ];

    protected $casts = [
        'formats' => 'array',
        'local_paths' => 'array',
        'remote_paths' => 'array',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->size_bytes;
        if ($bytes >= 1073741824) return round($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024) return round($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }
}

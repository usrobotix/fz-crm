<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-ys-l text-dc">Резервные копии</h2></x-slot>
    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        @if($queueIsSync)
        <div class="bg-dc-orange-120 border border-dc-orange-100/30 text-dc px-4 py-3 rounded-md text-ys-s">
            ⚠️ Очередь работает синхронно (QUEUE_CONNECTION=sync). Резервная копия создаётся в текущем запросе.
        </div>
        @endif

        {{-- Create backup form --}}
        <div class="bg-surface rounded-md shadow-dc-card p-5">
            <h3 class="font-semibold text-ys-m text-dc mb-4">Создать резервную копию</h3>
            <form method="POST" action="{{ route('admin.technical.backups.store') }}" class="flex flex-wrap gap-4 items-end">
                @csrf
                <div>
                    <label class="block text-ys-xs text-dc-secondary mb-1">Тип</label>
                    <select name="type" class="rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2 focus:outline-none focus:border-dc-blue-100">
                        <option value="db">База данных</option>
                        <option value="files">Файлы</option>
                        <option value="full">Полная</option>
                    </select>
                </div>
                <div>
                    <label class="block text-ys-xs text-dc-secondary mb-1">Формат</label>
                    <label class="flex items-center gap-2 text-ys-s text-dc"><input type="checkbox" name="formats[]" value="zip" checked> ZIP</label>
                </div>
                <button type="submit" class="px-5 py-2 bg-dc-primary text-white rounded-2xs text-ys-s font-medium hover:bg-dc-blue-200 dc-transition">Создать</button>
            </form>
        </div>

        {{-- Backups table --}}
        <div class="bg-surface rounded-md shadow-dc-card overflow-hidden">
            <table class="w-full text-ys-s" id="backups-table">
                <thead style="background-color:var(--color-surface-2);border-bottom:1px solid var(--color-border)">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">ID</th>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">Тип</th>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">Статус</th>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">Размер</th>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">Создан</th>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">Пользователь</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($backups as $backup)
                <tr class="dc-table-row border-b border-dc last:border-0" data-backup-id="{{ $backup->id }}" data-backup-status="{{ $backup->status }}">
                    <td class="dc-table-cell px-4 py-3 text-dc-secondary">#{{ $backup->id }}</td>
                    <td class="dc-table-cell px-4 py-3 text-dc">{{ $backup->type }}{{ $backup->kind === 'restore' ? ' (restore)' : '' }}</td>
                    <td class="dc-table-cell px-4 py-3">
                        <span class="backup-status" data-id="{{ $backup->id }}">
                            @if($backup->status === 'done')
                                <span class="text-dc-green-100 font-semibold">✓ Готово</span>
                            @elseif($backup->status === 'failed')
                                <span class="text-dc-red-100 font-semibold">✗ Ошибка</span>
                            @elseif($backup->status === 'running')
                                <span class="text-dc-orange-100">▶ Выполняется {{ $backup->progress_percent }}%</span>
                            @else
                                <span class="text-dc-secondary">⏳ В очереди</span>
                            @endif
                        </span>
                    </td>
                    <td class="dc-table-cell px-4 py-3 text-dc-secondary">{{ $backup->formatted_size }}</td>
                    <td class="dc-table-cell px-4 py-3 text-dc-secondary">{{ $backup->created_at->format('d.m.Y H:i') }}</td>
                    <td class="dc-table-cell px-4 py-3 text-dc-secondary">{{ $backup->user?->name ?? '—' }}</td>
                    <td class="dc-table-cell px-4 py-3 text-right space-x-2">
                        @if($backup->status === 'done' && $backup->kind !== 'restore')
                        <a href="{{ route('admin.technical.backups.download', $backup) }}" class="text-dc-primary text-ys-xs hover:underline">Скачать</a>
                        @if(in_array($backup->type, ['db','full']))
                        <form method="POST" action="{{ route('admin.technical.backups.restore', $backup) }}" class="inline"
                            onsubmit="return confirm('Восстановить БД из этой копии? Все текущие данные будут заменены.')">
                            @csrf
                            <input type="hidden" name="confirmed" value="1">
                            <button type="submit" class="text-dc-orange-100 text-ys-xs hover:underline">Восстановить</button>
                        </form>
                        @endif
                        @endif
                        <form method="POST" action="{{ route('admin.technical.backups.destroy', $backup) }}" class="inline"
                            onsubmit="return confirm('Удалить?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-dc-red-100 text-ys-xs hover:underline">Удалить</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-dc-secondary">Нет резервных копий.</td></tr>
                @endforelse
                </tbody>
            </table>
            <div class="px-4 py-3">{{ $backups->links() }}</div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script>
(function() {
    const restoreUuid = @json(session('restore_uuid'));

    function pollBackup(backupId) {
        const url = `/admin/technical/backups/${backupId}/status`;
        const row = document.querySelector(`tr[data-backup-id="${backupId}"]`);
        if (!row) return;

        const interval = setInterval(async () => {
            try {
                const r = await fetch(url);
                if (!r.ok) { clearInterval(interval); return; }
                const d = await r.json();
                const el = row.querySelector('.backup-status');
                if (!el) return;
                if (d.status === 'done') {
                    el.innerHTML = '<span class="text-dc-green-100 font-semibold">✓ Готово</span>';
                    clearInterval(interval);
                    setTimeout(() => location.reload(), 1500);
                } else if (d.status === 'failed') {
                    el.innerHTML = '<span class="text-dc-red-100 font-semibold">✗ Ошибка: ' + (d.error_message || '') + '</span>';
                    clearInterval(interval);
                } else {
                    el.innerHTML = '<span class="text-dc-orange-100">▶ ' + (d.current_step || 'Выполняется') + ' ' + (d.progress_percent || 0) + '%</span>';
                }
            } catch(e) { clearInterval(interval); }
        }, 3000);
    }

    function pollRestore(uuid) {
        const url = `/admin/technical/backups/restore/${uuid}/status`;
        const interval = setInterval(async () => {
            try {
                const r = await fetch(url);
                if (r.status === 404 || r.status === 410) { clearInterval(interval); return; }
                if (!r.ok) { clearInterval(interval); return; }
                const d = await r.json();
                if (d.status === 'done' || d.status === 'failed' || d.status === 'gone') {
                    clearInterval(interval);
                    if (d.status === 'done') setTimeout(() => location.reload(), 1000);
                }
            } catch(e) { clearInterval(interval); }
        }, 3000);
    }

    document.querySelectorAll('tr[data-backup-status]').forEach(row => {
        const status = row.dataset.backupStatus;
        if (status === 'queued' || status === 'running') {
            pollBackup(row.dataset.backupId);
        }
    });

    if (restoreUuid) pollRestore(restoreUuid);
})();
</script>
@endpush

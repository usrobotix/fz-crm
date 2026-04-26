<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('projects.show', $project) }}" class="text-dc-secondary hover:text-dc text-ys-s">← {{ $project->name }}</a>
                <div>
                    <h2 class="font-semibold text-ys-l text-dc">{{ $document->title }}</h2>
                    <p class="text-ys-xs text-dc-secondary">{{ $document->doc_type ?: '' }}{{ $document->doc_type && $document->assignee ? ' · ' : '' }}{{ $document->assignee?->name ? 'Исполнитель: '.$document->assignee->name : '' }}</p>
                </div>
            </div>
            <a href="{{ route('projects.documents.edit', [$project, $document]) }}" class="px-4 py-2 text-dc-primary border border-dc rounded-2xs text-ys-s hover:bg-surface-hover dc-transition">Редактировать</a>
        </div>
    </x-slot>
    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Meta --}}
        <div class="bg-surface rounded-md shadow-dc-card p-5">
            <dl class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-ys-s">
                <div>
                    <dt class="text-dc-secondary">Статус</dt>
                    @php
                    $docStatusClasses = [
                        'draft'       => 'bg-dc-gray-20 text-dc-secondary',
                        'in_progress' => 'bg-dc-blue-10 text-dc-blue-100',
                        'review'      => 'bg-dc-orange-120 text-dc-orange-100',
                        'approved'    => 'bg-dc-green-10 text-dc-green-100',
                        'final'       => 'bg-dc-green-10 text-dc-green-100 font-semibold',
                        'obsolete'    => 'bg-dc-red-10 text-dc-red-100',
                    ];
                    $docStatusLabels = [
                        'draft'=>'Черновик','in_progress'=>'В работе','review'=>'На проверке',
                        'approved'=>'Одобрен','final'=>'Финальный','obsolete'=>'Устарел',
                    ];
                    @endphp
                    <dd>
                        <span class="inline-flex px-2 py-0.5 rounded-3xs text-ys-xs {{ $docStatusClasses[$document->status] ?? 'text-dc-secondary' }}">
                            {{ $docStatusLabels[$document->status] ?? $document->status }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-dc-secondary">Версий</dt>
                    <dd class="font-medium text-dc">{{ $document->versions->count() }}</dd>
                </div>
                <div>
                    <dt class="text-dc-secondary">Последнее изменение</dt>
                    <dd class="text-dc">{{ $document->updated_at->format('d.m.Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-dc-secondary">Создан</dt>
                    <dd class="text-dc">{{ $document->created_at->format('d.m.Y') }}</dd>
                </div>
            </dl>
        </div>

        {{-- Content --}}
        @if($document->latestVersion)
        <div class="bg-surface rounded-md shadow-dc-card overflow-hidden">
            <div class="flex border-b border-dc">
                <button type="button" onclick="switchTab('source')" id="tab-source"
                    class="px-4 py-3 text-ys-s font-medium text-dc-primary border-b-2 border-dc-blue-100">Исходник</button>
                <button type="button" onclick="switchTab('rendered')" id="tab-rendered"
                    class="px-4 py-3 text-ys-s font-medium text-dc-secondary hover:text-dc">Предпросмотр</button>
            </div>
            <div id="pane-source" class="p-5">
                <pre class="text-ys-xs font-mono text-dc whitespace-pre-wrap overflow-x-auto">{{ $document->latestVersion->body }}</pre>
            </div>
            <div id="pane-rendered" class="hidden p-5 prose prose-sm max-w-none text-dc"></div>
        </div>
        @endif

        {{-- Version history --}}
        <div class="bg-surface rounded-md shadow-dc-card overflow-hidden">
            <h3 class="font-semibold text-ys-m text-dc px-5 py-4 border-b border-dc">История версий</h3>
            @if($document->versions->isEmpty())
            <p class="px-5 py-4 text-dc-secondary text-ys-s">Нет версий.</p>
            @else
            <table class="w-full text-ys-s">
                <thead style="background-color:var(--color-surface-2);border-bottom:1px solid var(--color-border)">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">Версия</th>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">Статус</th>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">Причина изменения</th>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">Автор</th>
                        <th class="px-4 py-3 text-right font-medium text-dc-secondary">Дата</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($document->versions->sortByDesc('version_number') as $ver)
                <tr class="dc-table-row border-b border-dc last:border-0">
                    <td class="dc-table-cell px-4 py-3 font-mono font-semibold text-dc-primary">v{{ $ver->version_number }}</td>
                    <td class="dc-table-cell px-4 py-3">
                        <span class="inline-flex px-2 py-0.5 rounded-3xs text-ys-xs {{ $docStatusClasses[$ver->status] ?? 'text-dc-secondary' }}">
                            {{ $docStatusLabels[$ver->status] ?? $ver->status }}
                        </span>
                    </td>
                    <td class="dc-table-cell px-4 py-3 text-dc">{{ $ver->change_note ?: '—' }}</td>
                    <td class="dc-table-cell px-4 py-3 text-dc-secondary">{{ $ver->user?->name ?? 'Система' }}</td>
                    <td class="dc-table-cell px-4 py-3 text-right text-dc-secondary">{{ $ver->created_at->format('d.m.Y H:i') }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
            @endif
        </div>

    </div>
</x-app-layout>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
const rawBody = @json($document->latestVersion?->body ?? '');

function switchTab(tab) {
    const source = document.getElementById('pane-source');
    const rendered = document.getElementById('pane-rendered');
    const tabSource = document.getElementById('tab-source');
    const tabRendered = document.getElementById('tab-rendered');
    if (tab === 'source') {
        source.classList.remove('hidden'); rendered.classList.add('hidden');
        tabSource.classList.add('text-dc-primary','border-b-2','border-dc-blue-100');
        tabSource.classList.remove('text-dc-secondary');
        tabRendered.classList.remove('text-dc-primary','border-b-2','border-dc-blue-100');
        tabRendered.classList.add('text-dc-secondary');
    } else {
        source.classList.add('hidden'); rendered.classList.remove('hidden');
        rendered.innerHTML = marked.parse(rawBody);
        tabRendered.classList.add('text-dc-primary','border-b-2','border-dc-blue-100');
        tabRendered.classList.remove('text-dc-secondary');
        tabSource.classList.remove('text-dc-primary','border-b-2','border-dc-blue-100');
        tabSource.classList.add('text-dc-secondary');
    }
}
</script>
@endpush

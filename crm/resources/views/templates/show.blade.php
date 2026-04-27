<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('templates.index') }}" class="text-dc-secondary hover:text-dc text-ys-s">← Шаблоны</a>
                <div>
                    <h2 class="font-semibold text-ys-l text-dc">{{ $template->title }}</h2>
                    <p class="text-ys-xs text-dc-secondary">{{ $template->law?->code }} · {{ $template->category }} · {{ $template->doc_type }}</p>
                </div>
            </div>
            <a href="{{ route('templates.edit', $template) }}" class="px-4 py-2 text-dc-primary border border-dc rounded-2xs text-ys-s hover:bg-surface-hover dc-transition">Редактировать</a>
        </div>
    </x-slot>
    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Metadata --}}
        <div class="bg-surface rounded-md shadow-dc-card p-5">
            <dl class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-ys-s">
                <div>
                    <dt class="text-dc-secondary">Статус</dt>
                    @php $stCls = ['draft'=>'text-dc-secondary','active'=>'text-dc-green-100','archived'=>'text-dc-red-100']; @endphp
                    <dd class="font-medium {{ $stCls[$template->status] ?? 'text-dc' }}">{{ $template->status }}</dd>
                </div>
                <div>
                    <dt class="text-dc-secondary">Источник</dt>
                    <dd class="text-dc">{{ $template->source ?: '—' }}</dd>
                </div>
                <div>
                    <dt class="text-dc-secondary">Версий</dt>
                    <dd class="text-dc">{{ $template->versions->count() }}</dd>
                </div>
                @if($template->repo_path)
                <div>
                    <dt class="text-dc-secondary">Репозиторий</dt>
                    <dd class="text-dc font-mono text-ys-xs truncate">{{ $template->repo_path }}</dd>
                </div>
                @endif
            </dl>
        </div>

        {{-- Content preview --}}
        @if($template->latestVersion)
        <div class="bg-surface rounded-md shadow-dc-card overflow-hidden">
            <div class="flex border-b border-dc">
                <button type="button" id="tab-source"
                    class="px-4 py-3 text-ys-s font-medium text-dc-primary border-b-2 border-dc-blue-100">Исходник</button>
                <button type="button" id="tab-rendered"
                    class="px-4 py-3 text-ys-s font-medium text-dc-secondary hover:text-dc">Предпросмотр</button>
            </div>
            <div id="pane-source" class="p-5">
                <pre class="text-ys-xs font-mono text-dc whitespace-pre-wrap overflow-x-auto">{{ $template->latestVersion->body }}</pre>
            </div>
            <div id="pane-rendered" class="hidden p-5 prose prose-sm max-w-none text-dc"></div>
        </div>
        @endif

        {{-- Version history --}}
        <div class="bg-surface rounded-md shadow-dc-card overflow-hidden">
            <h3 class="font-semibold text-ys-m text-dc px-5 py-4 border-b border-dc">История версий</h3>
            @if($template->versions->isEmpty())
            <p class="px-5 py-4 text-dc-secondary text-ys-s">Нет версий.</p>
            @else
            <table class="w-full text-ys-s">
                <thead style="background-color:var(--color-surface-2);border-bottom:1px solid var(--color-border)">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">Версия</th>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">Комментарий</th>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">Автор</th>
                        <th class="px-4 py-3 text-right font-medium text-dc-secondary">Дата</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($template->versions->sortByDesc('version_number') as $ver)
                <tr class="dc-table-row border-b border-dc last:border-0">
                    <td class="dc-table-cell px-4 py-3 font-mono font-semibold text-dc-primary">v{{ $ver->version_number }}</td>
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
document.addEventListener('DOMContentLoaded', function () {
    const rawBody = @json($template->latestVersion?->body ?? '');
    const source = document.getElementById('pane-source');
    const rendered = document.getElementById('pane-rendered');
    const tabSource = document.getElementById('tab-source');
    const tabRendered = document.getElementById('tab-rendered');

    if (!tabSource || !tabRendered) return;

    function setActiveTab(tab) {
        if (tab === 'source') {
            source.classList.remove('hidden');
            rendered.classList.add('hidden');
            tabSource.classList.add('text-dc-primary', 'border-b-2', 'border-dc-blue-100');
            tabSource.classList.remove('text-dc-secondary');
            tabRendered.classList.remove('text-dc-primary', 'border-b-2', 'border-dc-blue-100');
            tabRendered.classList.add('text-dc-secondary');
        } else {
            source.classList.add('hidden');
            rendered.classList.remove('hidden');
            if (typeof marked !== 'undefined') {
                rendered.innerHTML = marked.parse(rawBody);
            } else {
                rendered.innerHTML = '<p style="color:#b00">Ошибка: marked не загрузился</p>';
            }
            tabRendered.classList.add('text-dc-primary', 'border-b-2', 'border-dc-blue-100');
            tabRendered.classList.remove('text-dc-secondary');
            tabSource.classList.remove('text-dc-primary', 'border-b-2', 'border-dc-blue-100');
            tabSource.classList.add('text-dc-secondary');
        }
    }

    tabSource.addEventListener('click', function () { setActiveTab('source'); });
    tabRendered.addEventListener('click', function () { setActiveTab('rendered'); });
});
</script>
@endpush

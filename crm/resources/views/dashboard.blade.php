<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-ys-l text-dc">Дашборд</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Status counts --}}
            <div>
                <h3 class="text-ys-m font-semibold text-dc mb-3">Документы по статусам (активные проекты)</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                    @php
                    $statusLabels = [
                        'draft'       => 'Черновик',
                        'in_progress' => 'В работе',
                        'review'      => 'На проверке',
                        'approved'    => 'Одобрен',
                        'final'       => 'Финальный',
                        'obsolete'    => 'Устарел',
                    ];
                    @endphp
                    @foreach(['draft','in_progress','review','approved','final','obsolete'] as $s)
                    <div class="rounded-md p-4 text-center shadow-dc-card bg-surface">
                        <div class="text-ys-xxl font-bold text-dc">{{ $statusCounts[$s] ?? 0 }}</div>
                        <div class="text-ys-xs text-dc-secondary mt-1">{{ $statusLabels[$s] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Upcoming projects --}}
                <div class="rounded-md shadow-dc-card bg-surface p-5">
                    <h3 class="text-ys-m font-semibold text-dc mb-3">Ближайшие дедлайны</h3>
                    @if($upcomingProjects->isEmpty())
                        <p class="text-dc-secondary text-ys-s">Нет проектов с дедлайнами.</p>
                    @else
                    <table class="w-full text-ys-s">
                        <thead><tr class="text-dc-secondary border-b border-dc">
                            <th class="pb-2 text-left font-medium">Проект</th>
                            <th class="pb-2 text-left font-medium">Компания</th>
                            <th class="pb-2 text-right font-medium">Дедлайн</th>
                        </tr></thead>
                        <tbody>
                        @foreach($upcomingProjects as $proj)
                        <tr class="border-b border-dc last:border-0">
                            <td class="py-2"><a href="{{ route('projects.show', $proj) }}" class="text-dc-primary hover:underline">{{ $proj->name }}</a></td>
                            <td class="py-2 text-dc-secondary">{{ $proj->company->name }}</td>
                            <td class="py-2 text-right {{ $proj->due_at->isPast() ? 'text-dc-red-100 font-semibold' : 'text-dc' }}">{{ $proj->due_at->format('d.m.Y') }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>

                {{-- Recent changes --}}
                <div class="rounded-md shadow-dc-card bg-surface p-5">
                    <h3 class="text-ys-m font-semibold text-dc mb-3">Последние изменения документов</h3>
                    @if($recentVersions->isEmpty())
                        <p class="text-dc-secondary text-ys-s">Нет изменений.</p>
                    @else
                    <div class="space-y-2">
                        @foreach($recentVersions as $ver)
                        <div class="flex items-start gap-3 text-ys-s">
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('projects.documents.show', [$ver->document->project_id, $ver->document_id]) }}" class="text-dc-primary hover:underline truncate block">{{ $ver->document->title }}</a>
                                <span class="text-dc-secondary">v{{ $ver->version_number }} · {{ $ver->user?->name ?? 'Импорт' }} · {{ $ver->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

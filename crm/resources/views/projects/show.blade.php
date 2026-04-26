<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('projects.index') }}" class="text-dc-secondary hover:text-dc text-ys-s">← Проекты</a>
                <div>
                    <h2 class="font-semibold text-ys-l text-dc">{{ $project->name }}</h2>
                    <p class="text-ys-xs text-dc-secondary">{{ $project->company->name }} · {{ $project->law?->code }}</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('projects.export', $project) }}" class="px-4 py-2 border border-dc rounded-2xs text-ys-s text-dc-secondary hover:bg-surface-hover dc-transition">↓ Экспорт</a>
                <a href="{{ route('projects.edit', $project) }}" class="px-4 py-2 text-dc-primary border border-dc rounded-2xs text-ys-s hover:bg-surface-hover dc-transition">Редактировать</a>
            </div>
        </div>
    </x-slot>
    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Project meta --}}
        <div class="bg-surface rounded-md shadow-dc-card p-5">
            <dl class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-ys-s">
                <div>
                    <dt class="text-dc-secondary">Статус</dt>
                    @php
                    $statusLabels = ['active'=>'Активный','paused'=>'Приостановлен','completed'=>'Завершён','archived'=>'Архив'];
                    $statusClasses = ['active'=>'text-dc-green-100','paused'=>'text-dc-orange-100','completed'=>'text-dc-secondary','archived'=>'text-dc-disabled'];
                    @endphp
                    <dd class="font-medium {{ $statusClasses[$project->status] ?? 'text-dc' }}">{{ $statusLabels[$project->status] ?? $project->status }}</dd>
                </div>
                <div>
                    <dt class="text-dc-secondary">Дедлайн</dt>
                    <dd class="font-medium {{ $project->due_at?->isPast() ? 'text-dc-red-100' : 'text-dc' }}">
                        {{ $project->due_at?->format('d.m.Y') ?: '—' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-dc-secondary">Документов</dt>
                    <dd class="font-medium text-dc">{{ $project->documents->count() }}</dd>
                </div>
                <div>
                    <dt class="text-dc-secondary">Создан</dt>
                    <dd class="text-dc">{{ $project->created_at->format('d.m.Y') }}</dd>
                </div>
            </dl>
            @if($project->notes)
            <p class="mt-4 text-dc-secondary text-ys-s whitespace-pre-wrap">{{ $project->notes }}</p>
            @endif
        </div>

        {{-- Documents --}}
        <div class="bg-surface rounded-md shadow-dc-card overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-dc">
                <h3 class="font-semibold text-ys-m text-dc">Документы</h3>
                <a href="{{ route('projects.documents.create', $project) }}" class="px-4 py-2 bg-dc-primary text-white rounded-2xs text-ys-s font-medium hover:bg-dc-blue-200 dc-transition">+ Документ</a>
            </div>
            @if($project->documents->isEmpty())
            <div class="px-5 py-8 text-center text-dc-secondary text-ys-s">
                Нет документов. <a href="{{ route('projects.documents.create', $project) }}" class="text-dc-primary hover:underline">Добавить первый</a>
            </div>
            @else
            <table class="w-full text-ys-s">
                <thead style="background-color:var(--color-surface-2);border-bottom:1px solid var(--color-border)">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">Название</th>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">Тип</th>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">Статус</th>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">Исполнитель</th>
                        <th class="px-4 py-3 text-right font-medium text-dc-secondary">Обновлён</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($project->documents as $doc)
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
                <tr class="dc-table-row border-b border-dc last:border-0">
                    <td class="dc-table-cell px-4 py-3 text-dc">
                        <a href="{{ route('projects.documents.show', [$project, $doc]) }}" class="hover:underline">{{ $doc->title }}</a>
                    </td>
                    <td class="dc-table-cell px-4 py-3 text-dc-secondary">{{ $doc->doc_type ?: '—' }}</td>
                    <td class="dc-table-cell px-4 py-3">
                        <span class="inline-flex px-2 py-0.5 rounded-3xs text-ys-xs {{ $docStatusClasses[$doc->status] ?? 'text-dc-secondary' }}">
                            {{ $docStatusLabels[$doc->status] ?? $doc->status }}
                        </span>
                    </td>
                    <td class="dc-table-cell px-4 py-3 text-dc-secondary">{{ $doc->assignee?->name ?: '—' }}</td>
                    <td class="dc-table-cell px-4 py-3 text-right text-dc-secondary">{{ $doc->updated_at->format('d.m.Y') }}</td>
                    <td class="dc-table-cell px-4 py-3 text-right space-x-3">
                        <a href="{{ route('projects.documents.show', [$project, $doc]) }}" class="text-dc-primary text-ys-xs hover:underline">Открыть</a>
                        <a href="{{ route('projects.documents.edit', [$project, $doc]) }}" class="text-dc-secondary text-ys-xs hover:underline">Изменить</a>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            @endif
        </div>

    </div>
</x-app-layout>

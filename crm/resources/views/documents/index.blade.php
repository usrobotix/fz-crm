<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('projects.show', $project) }}" class="text-dc-secondary hover:text-dc text-ys-s">← {{ $project->name }}</a>
                <h2 class="font-semibold text-ys-l text-dc">Документы проекта</h2>
            </div>
            <a href="{{ route('projects.documents.create', $project) }}" class="px-4 py-2 bg-dc-primary text-white rounded-2xs text-ys-s font-medium hover:bg-dc-blue-200 dc-transition">+ Документ</a>
        </div>
    </x-slot>
    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-surface rounded-md shadow-dc-card overflow-hidden">
            <table class="w-full text-ys-s">
                <thead style="background-color:var(--color-surface-2);border-bottom:1px solid var(--color-border)">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">Название</th>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">Статус</th>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">Тип</th>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">Исполнитель</th>
                        <th class="px-4 py-3 text-right font-medium text-dc-secondary">Обновлён</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($documents as $doc)
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
                    <td class="dc-table-cell px-4 py-3 font-medium text-dc">
                        <a href="{{ route('projects.documents.show', [$project, $doc]) }}" class="hover:underline">{{ $doc->title }}</a>
                    </td>
                    <td class="dc-table-cell px-4 py-3">
                        <span class="inline-flex px-2 py-0.5 rounded-3xs text-ys-xs {{ $docStatusClasses[$doc->status] ?? 'text-dc-secondary' }}">
                            {{ $docStatusLabels[$doc->status] ?? $doc->status }}
                        </span>
                    </td>
                    <td class="dc-table-cell px-4 py-3 text-dc-secondary">{{ $doc->doc_type ?: '—' }}</td>
                    <td class="dc-table-cell px-4 py-3 text-dc-secondary">{{ $doc->assignee?->name ?: '—' }}</td>
                    <td class="dc-table-cell px-4 py-3 text-right text-dc-secondary">{{ $doc->updated_at->format('d.m.Y') }}</td>
                    <td class="dc-table-cell px-4 py-3 text-right space-x-3">
                        <a href="{{ route('projects.documents.show', [$project, $doc]) }}" class="text-dc-primary text-ys-xs hover:underline">Открыть</a>
                        <a href="{{ route('projects.documents.edit', [$project, $doc]) }}" class="text-dc-secondary text-ys-xs hover:underline">Изменить</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-dc-secondary">Нет документов. <a href="{{ route('projects.documents.create', $project) }}" class="text-dc-primary hover:underline">Добавить первый</a></td></tr>
                @endforelse
                </tbody>
            </table>
            @if(isset($documents) && method_exists($documents, 'hasPages') && $documents->hasPages())
            <div class="px-4 py-3 border-t border-dc">{{ $documents->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>

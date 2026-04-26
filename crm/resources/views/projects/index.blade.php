<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-ys-l text-dc">Проекты</h2>
            <a href="{{ route('projects.create') }}" class="px-4 py-2 bg-dc-primary text-white rounded-2xs text-ys-s font-medium hover:bg-dc-blue-200 dc-transition">+ Добавить</a>
        </div>
    </x-slot>
    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-surface rounded-md shadow-dc-card overflow-hidden">
            <table class="w-full text-ys-s">
                <thead style="background-color:var(--color-surface-2);border-bottom:1px solid var(--color-border)">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">Проект</th>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">Компания</th>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">Закон</th>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">Статус</th>
                        <th class="px-4 py-3 text-right font-medium text-dc-secondary">Дедлайн</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($projects as $project)
                <tr class="dc-table-row border-b border-dc last:border-0">
                    <td class="dc-table-cell px-4 py-3 font-medium text-dc">
                        <a href="{{ route('projects.show', $project) }}" class="hover:underline">{{ $project->name }}</a>
                    </td>
                    <td class="dc-table-cell px-4 py-3 text-dc-secondary">
                        <a href="{{ route('companies.show', $project->company) }}" class="hover:underline">{{ $project->company->name }}</a>
                    </td>
                    <td class="dc-table-cell px-4 py-3 text-dc-secondary font-mono">{{ $project->law?->code ?: '—' }}</td>
                    <td class="dc-table-cell px-4 py-3">
                        @php
                        $statusClasses = [
                            'active'    => 'text-dc-green-100',
                            'paused'    => 'text-dc-orange-100',
                            'completed' => 'text-dc-secondary',
                            'archived'  => 'text-dc-disabled',
                        ];
                        $statusLabels = [
                            'active'    => 'Активный',
                            'paused'    => 'Приостановлен',
                            'completed' => 'Завершён',
                            'archived'  => 'Архив',
                        ];
                        @endphp
                        <span class="{{ $statusClasses[$project->status] ?? 'text-dc-secondary' }}">
                            {{ $statusLabels[$project->status] ?? $project->status }}
                        </span>
                    </td>
                    <td class="dc-table-cell px-4 py-3 text-right {{ $project->due_at?->isPast() ? 'text-dc-red-100 font-semibold' : 'text-dc-secondary' }}">
                        {{ $project->due_at?->format('d.m.Y') ?: '—' }}
                    </td>
                    <td class="dc-table-cell px-4 py-3 text-right space-x-3">
                        <a href="{{ route('projects.show', $project) }}" class="text-dc-primary text-ys-xs hover:underline">Открыть</a>
                        <a href="{{ route('projects.edit', $project) }}" class="text-dc-secondary text-ys-xs hover:underline">Изменить</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-dc-secondary">Нет проектов. <a href="{{ route('projects.create') }}" class="text-dc-primary hover:underline">Создать первый</a></td></tr>
                @endforelse
                </tbody>
            </table>
            @if($projects->hasPages())
            <div class="px-4 py-3 border-t border-dc">{{ $projects->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>

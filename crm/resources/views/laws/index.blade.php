<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-ys-l text-dc">Законы / Фреймворки</h2>
            <a href="{{ route('laws.create') }}" class="px-4 py-2 bg-dc-primary text-white rounded-2xs text-ys-s font-medium hover:bg-dc-blue-200 dc-transition">+ Добавить</a>
        </div>
    </x-slot>
    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-surface rounded-md shadow-dc-card overflow-hidden">
            <table class="w-full text-ys-s">
                <thead style="background-color:var(--color-surface-2);border-bottom:1px solid var(--color-border)">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">Код</th>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">Название</th>
                        <th class="px-4 py-3 text-center font-medium text-dc-secondary">Шаблонов</th>
                        <th class="px-4 py-3 text-center font-medium text-dc-secondary">Проектов</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($laws as $law)
                <tr class="dc-table-row border-b border-dc last:border-0">
                    <td class="dc-table-cell px-4 py-3 font-mono font-semibold text-dc-primary">{{ $law->code }}</td>
                    <td class="dc-table-cell px-4 py-3 text-dc"><a href="{{ route('laws.show', $law) }}" class="hover:underline">{{ $law->name }}</a></td>
                    <td class="dc-table-cell px-4 py-3 text-center text-dc-secondary">{{ $law->templates_count }}</td>
                    <td class="dc-table-cell px-4 py-3 text-center text-dc-secondary">{{ $law->projects_count }}</td>
                    <td class="dc-table-cell px-4 py-3 text-right">
                        <a href="{{ route('laws.edit', $law) }}" class="text-dc-primary text-ys-xs hover:underline">Изменить</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-dc-secondary">Нет законов. <a href="{{ route('laws.create') }}" class="text-dc-primary hover:underline">Добавить первый</a></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

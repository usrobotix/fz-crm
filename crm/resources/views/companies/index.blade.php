<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-ys-l text-dc">Компании</h2>
            <a href="{{ route('companies.create') }}" class="px-4 py-2 bg-dc-primary text-white rounded-2xs text-ys-s font-medium hover:bg-dc-blue-200 dc-transition">+ Добавить</a>
        </div>
    </x-slot>
    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-surface rounded-md shadow-dc-card overflow-hidden">
            <table class="w-full text-ys-s">
                <thead style="background-color:var(--color-surface-2);border-bottom:1px solid var(--color-border)">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">Название</th>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">ИНН</th>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">Контактное лицо</th>
                        <th class="px-4 py-3 text-center font-medium text-dc-secondary">Проектов</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($companies as $company)
                <tr class="dc-table-row border-b border-dc last:border-0">
                    <td class="dc-table-cell px-4 py-3 font-medium text-dc">
                        <a href="{{ route('companies.show', $company) }}" class="hover:underline">{{ $company->name }}</a>
                    </td>
                    <td class="dc-table-cell px-4 py-3 text-dc-secondary font-mono">{{ $company->inn ?: '—' }}</td>
                    <td class="dc-table-cell px-4 py-3 text-dc-secondary">{{ $company->contact_person ?: '—' }}</td>
                    <td class="dc-table-cell px-4 py-3 text-center text-dc-secondary">{{ $company->projects_count }}</td>
                    <td class="dc-table-cell px-4 py-3 text-right space-x-3">
                        <a href="{{ route('companies.show', $company) }}" class="text-dc-primary text-ys-xs hover:underline">Открыть</a>
                        <a href="{{ route('companies.edit', $company) }}" class="text-dc-secondary text-ys-xs hover:underline">Изменить</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-dc-secondary">Нет компаний. <a href="{{ route('companies.create') }}" class="text-dc-primary hover:underline">Добавить первую</a></td></tr>
                @endforelse
                </tbody>
            </table>
            @if($companies->hasPages())
            <div class="px-4 py-3 border-t border-dc">{{ $companies->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>

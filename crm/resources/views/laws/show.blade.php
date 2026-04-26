<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('laws.index') }}" class="text-dc-secondary hover:text-dc text-ys-s">← Законы</a>
            <h2 class="font-semibold text-ys-l text-dc">{{ $law->code }}: {{ $law->name }}</h2>
        </div>
    </x-slot>
    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="flex items-center justify-between">
            @if($law->description)
            <p class="text-dc-secondary text-ys-s">{{ $law->description }}</p>
            @else
            <span></span>
            @endif
            <a href="{{ route('laws.edit', $law) }}" class="px-4 py-2 text-dc-primary border border-dc rounded-2xs text-ys-s hover:bg-surface-hover dc-transition">Редактировать</a>
        </div>

        <div class="bg-surface rounded-md shadow-dc-card p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-ys-m text-dc">Шаблоны ({{ $law->templates->count() }})</h3>
                <a href="{{ route('templates.create') }}" class="px-4 py-2 bg-dc-primary text-white rounded-2xs text-ys-s font-medium hover:bg-dc-blue-200 dc-transition">+ Шаблон</a>
            </div>
            @if($law->templates->isEmpty())
            <p class="text-dc-secondary text-ys-s">Нет шаблонов.</p>
            @else
            <table class="w-full text-ys-s">
                <thead><tr class="text-dc-secondary border-b border-dc">
                    <th class="pb-2 text-left font-medium">Категория</th>
                    <th class="pb-2 text-left font-medium">Название</th>
                    <th class="pb-2 text-left font-medium">Тип</th>
                    <th class="pb-2 text-left font-medium">Статус</th>
                    <th class="pb-2"></th>
                </tr></thead>
                <tbody>
                @foreach($law->templates as $tpl)
                <tr class="dc-table-row border-b border-dc last:border-0">
                    <td class="dc-table-cell py-2 text-dc-secondary">{{ $tpl->category }}</td>
                    <td class="dc-table-cell py-2"><a href="{{ route('templates.show', $tpl) }}" class="text-dc-primary hover:underline">{{ $tpl->title }}</a></td>
                    <td class="dc-table-cell py-2 text-dc-secondary">{{ $tpl->doc_type }}</td>
                    <td class="dc-table-cell py-2 text-dc-secondary">{{ $tpl->status }}</td>
                    <td class="dc-table-cell py-2 text-right">
                        <a href="{{ route('templates.edit', $tpl) }}" class="text-dc-primary text-ys-xs hover:underline">Изменить</a>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-ys-l text-dc">Шаблоны документов</h2>
            <a href="{{ route('templates.create') }}" class="px-4 py-2 bg-dc-primary text-white rounded-2xs text-ys-s font-medium hover:bg-dc-blue-200 dc-transition">+ Добавить</a>
        </div>
    </x-slot>
    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">

        {{-- Filters --}}
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-ys-xs text-dc-secondary mb-1">Закон</label>
                <select name="law_id" onchange="this.form.submit()"
                    class="rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2 focus:outline-none focus:border-dc-blue-100">
                    <option value="">Все законы</option>
                    @foreach($laws as $law)
                    <option value="{{ $law->id }}" {{ request('law_id') == $law->id ? 'selected' : '' }}>{{ $law->code }} — {{ $law->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-ys-xs text-dc-secondary mb-1">Статус</label>
                <select name="status" onchange="this.form.submit()"
                    class="rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2 focus:outline-none focus:border-dc-blue-100">
                    <option value="">Все статусы</option>
                    @foreach(['draft'=>'Черновик','active'=>'Активный','archived'=>'Устаревший'] as $val => $lbl)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-ys-xs text-dc-secondary mb-1">Поиск</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Название..."
                    class="rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2 focus:outline-none focus:border-dc-blue-100">
            </div>
            <button type="submit" class="px-4 py-2 border border-dc rounded-2xs text-ys-s text-dc-secondary hover:bg-surface-hover dc-transition">Найти</button>
            @if(request('law_id') || request('status') || request('search'))
            <a href="{{ route('templates.index') }}" class="px-4 py-2 text-dc-secondary text-ys-s hover:text-dc dc-transition">Сбросить</a>
            @endif
        </form>

        <div class="bg-surface rounded-md shadow-dc-card overflow-hidden">
            <table class="w-full text-ys-s">
                <thead style="background-color:var(--color-surface-2);border-bottom:1px solid var(--color-border)">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">Название</th>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">Категория</th>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">Закон</th>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">Тип</th>
                        <th class="px-4 py-3 text-left font-medium text-dc-secondary">Статус</th>
                        <th class="px-4 py-3 text-center font-medium text-dc-secondary">Версий</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($templates as $tpl)
                <tr class="dc-table-row border-b border-dc last:border-0">
                    <td class="dc-table-cell px-4 py-3 font-medium text-dc">
                        <a href="{{ route('templates.show', $tpl) }}" class="hover:underline">{{ $tpl->title }}</a>
                    </td>
                    <td class="dc-table-cell px-4 py-3 text-dc-secondary">{{ $tpl->category ?: '—' }}</td>
                    <td class="dc-table-cell px-4 py-3 text-dc-secondary font-mono">{{ $tpl->law?->code ?: '—' }}</td>
                    <td class="dc-table-cell px-4 py-3 text-dc-secondary">{{ $tpl->doc_type ?: '—' }}</td>
                    <td class="dc-table-cell px-4 py-3">
                        @php
                        $stCls = ['draft'=>'text-dc-secondary','active'=>'text-dc-green-100','archived'=>'text-dc-red-100'];
                        $stLbl = ['draft'=>'Черновик','active'=>'Активный','archived'=>'Устаревший'];
                        @endphp
                        <span class="{{ $stCls[$tpl->status] ?? 'text-dc-secondary' }}">{{ $stLbl[$tpl->status] ?? $tpl->status }}</span>
                    </td>
                    <td class="dc-table-cell px-4 py-3 text-center text-dc-secondary">{{ $tpl->versions_count }}</td>
                    <td class="dc-table-cell px-4 py-3 text-right space-x-3">
                        <a href="{{ route('templates.show', $tpl) }}" class="text-dc-primary text-ys-xs hover:underline">Открыть</a>
                        <a href="{{ route('templates.edit', $tpl) }}" class="text-dc-secondary text-ys-xs hover:underline">Изменить</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-dc-secondary">Шаблоны не найдены.</td></tr>
                @endforelse
                </tbody>
            </table>
            @if($templates->hasPages())
            <div class="px-4 py-3 border-t border-dc">{{ $templates->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>

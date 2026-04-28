<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-ys-l text-dc">Законы / Фреймворки</h2>
            <a href="{{ route('laws.create') }}"
               class="px-4 py-2 bg-dc-primary text-white rounded-2xs text-ys-s font-medium hover:bg-dc-blue-200 dc-transition">
                + Добавить
            </a>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
        <form method="GET" action="{{ route('laws.index') }}"
              class="bg-surface rounded-md shadow-dc-card p-4 flex flex-col gap-3">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div class="md:col-span-2">
                    <label class="block text-ys-xs text-dc-secondary mb-1">Поиск</label>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="код, название, ключевые слова…"
                           class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2 focus:outline-none focus:border-dc-blue-100">
                </div>

                <div>
                    <label class="block text-ys-xs text-dc-secondary mb-1">Страна</label>
                    <select name="country"
                            class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">
                        <option value="">Все</option>
                        @foreach($countries as $c)
                            <option value="{{ $c }}" @selected(request('country')===$c)>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-ys-xs text-dc-secondary mb-1">Тип</label>
                    <select name="type"
                            class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">
                        <option value="">Все</option>
                        @foreach($types as $t)
                            <option value="{{ $t }}" @selected(request('type')===$t)>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                <div>
                    <label class="block text-ys-xs text-dc-secondary mb-1">Статус</label>
                    <select name="status"
                            class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">
                        <option value="">Все</option>
                        @foreach($statuses as $s)
                            <option value="{{ $s }}" @selected(request('status')===$s)>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-ys-xs text-dc-secondary mb-1">Сортировка</label>
                    <select name="sort"
                            class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">
                        <option value="code" @selected(request('sort','code')==='code')>Код</option>
                        <option value="name" @selected(request('sort')==='name')>Название</option>
                        <option value="published_at" @selected(request('sort')==='published_at')>Дата</option>
                        <option value="templates_count" @selected(request('sort')==='templates_count')>Шаблоны</option>
                        <option value="projects_count" @selected(request('sort')==='projects_count')>Проекты</option>
                    </select>
                </div>

                <div>
                    <label class="block text-ys-xs text-dc-secondary mb-1">Направление</label>
                    <select name="dir"
                            class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">
                        <option value="asc" @selected(request('dir','asc')==='asc')>↑</option>
                        <option value="desc" @selected(request('dir')==='desc')>↓</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                            class="px-4 py-2 bg-dc-primary text-white rounded-2xs text-ys-s font-medium hover:bg-dc-blue-200 dc-transition">
                        Применить
                    </button>
                    <a href="{{ route('laws.index') }}"
                       class="px-4 py-2 text-dc-secondary hover:text-dc text-ys-s dc-transition">
                        Сброс
                    </a>
                </div>
            </div>
        </form>

        <div class="bg-surface rounded-md shadow-dc-card overflow-hidden">
            <table class="w-full text-ys-s">
                <thead style="background-color:var(--color-surface-2);border-bottom:1px solid var(--color-border)">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-dc-secondary">Код</th>
                    <th class="px-4 py-3 text-left font-medium text-dc-secondary">Название</th>
                    <th class="px-4 py-3 text-left font-medium text-dc-secondary">Страна</th>
                    <th class="px-4 py-3 text-left font-medium text-dc-secondary">Тип</th>
                    <th class="px-4 py-3 text-left font-medium text-dc-secondary">Статус</th>
                    <th class="px-4 py-3 text-center font-medium text-dc-secondary">Шаблонов</th>
                    <th class="px-4 py-3 text-center font-medium text-dc-secondary">Проектов</th>
                    <th class="px-4 py-3"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($laws as $law)
                    <tr class="dc-table-row border-b border-dc last:border-0">
                        <td class="dc-table-cell px-4 py-3 font-mono font-semibold text-dc-primary">{{ $law->code }}</td>
                        <td class="dc-table-cell px-4 py-3 text-dc">
                            <a href="{{ route('laws.show', $law) }}" class="hover:underline">{{ $law->name }}</a>
                            @if($law->published_at)
                                <div class="text-ys-xs text-dc-secondary mt-1">{{ $law->published_at->format('Y-m-d') }}</div>
                            @endif
                        </td>
                        <td class="dc-table-cell px-4 py-3 text-dc-secondary">{{ $law->country }}</td>
                        <td class="dc-table-cell px-4 py-3 text-dc-secondary">{{ $law->type }}</td>
                        <td class="dc-table-cell px-4 py-3 text-dc-secondary">{{ $law->status }}</td>
                        <td class="dc-table-cell px-4 py-3 text-center text-dc-secondary">{{ $law->templates_count }}</td>
                        <td class="dc-table-cell px-4 py-3 text-center text-dc-secondary">{{ $law->projects_count }}</td>
                        <td class="dc-table-cell px-4 py-3 text-right">
                            <a href="{{ route('laws.edit', $law) }}" class="text-dc-primary text-ys-xs hover:underline">Изменить</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-dc-secondary">
                            Нет законов. <a href="{{ route('laws.create') }}" class="text-dc-primary hover:underline">Добавить первый</a>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $laws->links() }}
        </div>
    </div>
</x-app-layout>
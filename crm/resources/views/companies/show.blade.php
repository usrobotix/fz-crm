<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('companies.index') }}" class="text-dc-secondary hover:text-dc text-ys-s">← Компании</a>
                <h2 class="font-semibold text-ys-l text-dc">{{ $company->name }}</h2>
            </div>
            <a href="{{ route('companies.edit', $company) }}" class="px-4 py-2 text-dc-primary border border-dc rounded-2xs text-ys-s hover:bg-surface-hover dc-transition">Редактировать</a>
        </div>
    </x-slot>
    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Company details --}}
        <div class="bg-surface rounded-md shadow-dc-card p-5">
            <h3 class="font-semibold text-ys-m text-dc mb-4">Реквизиты</h3>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-ys-s">
                @if($company->inn)
                <div>
                    <dt class="text-dc-secondary">ИНН</dt>
                    <dd class="text-dc font-mono">{{ $company->inn }}</dd>
                </div>
                @endif
                @if($company->contact_person)
                <div>
                    <dt class="text-dc-secondary">Контактное лицо</dt>
                    <dd class="text-dc">{{ $company->contact_person }}</dd>
                </div>
                @endif
                @if($company->email)
                <div>
                    <dt class="text-dc-secondary">Email</dt>
                    <dd class="text-dc"><a href="mailto:{{ $company->email }}" class="text-dc-primary hover:underline">{{ $company->email }}</a></dd>
                </div>
                @endif
                @if($company->phone)
                <div>
                    <dt class="text-dc-secondary">Телефон</dt>
                    <dd class="text-dc">{{ $company->phone }}</dd>
                </div>
                @endif
                @if($company->notes)
                <div class="sm:col-span-2">
                    <dt class="text-dc-secondary">Примечания</dt>
                    <dd class="text-dc whitespace-pre-wrap">{{ $company->notes }}</dd>
                </div>
                @endif
            </dl>
        </div>

        {{-- Projects list --}}
        <div class="bg-surface rounded-md shadow-dc-card p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-ys-m text-dc">Проекты ({{ $company->projects->count() }})</h3>
                <a href="{{ route('projects.create') }}" class="px-4 py-2 bg-dc-primary text-white rounded-2xs text-ys-s font-medium hover:bg-dc-blue-200 dc-transition">+ Проект</a>
            </div>
            @if($company->projects->isEmpty())
            <p class="text-dc-secondary text-ys-s">Нет проектов.</p>
            @else
            <table class="w-full text-ys-s">
                <thead><tr class="text-dc-secondary border-b border-dc">
                    <th class="pb-2 text-left font-medium">Название</th>
                    <th class="pb-2 text-left font-medium">Закон</th>
                    <th class="pb-2 text-left font-medium">Статус</th>
                    <th class="pb-2 text-right font-medium">Дедлайн</th>
                </tr></thead>
                <tbody>
                @foreach($company->projects as $project)
                <tr class="dc-table-row border-b border-dc last:border-0">
                    <td class="dc-table-cell py-2">
                        <a href="{{ route('projects.show', $project) }}" class="text-dc-primary hover:underline">{{ $project->name }}</a>
                    </td>
                    <td class="dc-table-cell py-2 text-dc-secondary">{{ $project->law?->code }}</td>
                    <td class="dc-table-cell py-2 text-dc-secondary">{{ $project->status }}</td>
                    <td class="dc-table-cell py-2 text-right {{ $project->due_at?->isPast() ? 'text-dc-red-100 font-semibold' : 'text-dc-secondary' }}">
                        {{ $project->due_at?->format('d.m.Y') ?: '—' }}
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            @endif
        </div>

    </div>
</x-app-layout>

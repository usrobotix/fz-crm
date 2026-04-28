<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('laws.index') }}" class="text-dc-secondary hover:text-dc text-ys-s">← Законы</a>
            <h2 class="font-semibold text-ys-l text-dc">{{ $law->code }}: {{ $law->name }}</h2>
        </div>
    </x-slot>

    @php
        $tabs = [
            'overview'   => 'Описание',
            'articles'   => 'Статьи',
            'documents'  => 'Документы',
            'templates'  => 'Шаблоны',
            'expertise'  => 'Экспертиза',
        ];
        $activeTab = request('tab', 'overview');
        if (!array_key_exists($activeTab, $tabs)) $activeTab = 'overview';
    @endphp

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="flex items-start justify-between gap-4">
            <div class="space-y-2">
                <div class="text-ys-s text-dc-secondary">
                    <span class="font-mono text-dc-primary font-semibold">{{ $law->code }}</span>
                    @if($law->country) <span class="ml-2">Страна: <span class="text-dc">{{ $law->country }}</span></span> @endif
                    @if($law->type) <span class="ml-2">Тип: <span class="text-dc">{{ $law->type }}</span></span> @endif
                    @if($law->status) <span class="ml-2">Статус: <span class="text-dc">{{ $law->status }}</span></span> @endif
                    @if($law->published_at) <span class="ml-2">Дата: <span class="text-dc">{{ $law->published_at->format('Y-m-d') }}</span></span> @endif
                </div>

                @if(!empty($law->tags))
                    <div class="flex flex-wrap gap-2">
                        @foreach($law->tags as $tag)
                            <span class="px-2 py-1 rounded-2xs text-ys-xs border border-dc text-dc-secondary">{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="flex gap-2">
                <a href="{{ route('laws.edit', $law) }}"
                   class="px-4 py-2 text-dc-primary border border-dc rounded-2xs text-ys-s hover:bg-surface-hover dc-transition">
                    Редактировать
                </a>
            </div>
        </div>

        <div class="bg-surface rounded-md shadow-dc-card p-4">
            <nav class="flex flex-wrap gap-2">
                @foreach($tabs as $key => $label)
                    <a href="{{ route('laws.show', $law) }}?tab={{ $key }}"
                       class="px-3 py-2 rounded-2xs text-ys-s border dc-transition
                              {{ $activeTab === $key ? 'bg-surface-2 border-dc text-dc' : 'border-transparent text-dc-secondary hover:text-dc hover:bg-surface-hover' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </nav>
        </div>

        {{-- TAB CONTENT --}}
        @if($activeTab === 'overview')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-surface rounded-md shadow-dc-card p-5">
                        <h3 class="font-semibold text-ys-m text-dc mb-3">Описание</h3>
                        @if($law->description)
                            <p class="text-ys-s text-dc whitespace-pre-wrap">{{ $law->description }}</p>
                        @else
                            <p class="text-ys-s text-dc-secondary">Описание не заполнено.</p>
                        @endif
                    </div>

                    <div class="bg-surface rounded-md shadow-dc-card p-5">
                        <h3 class="font-semibold text-ys-m text-dc mb-3">Комментарии</h3>
                        @if($law->comment)
                            <p class="text-ys-s text-dc whitespace-pre-wrap">{{ $law->comment }}</p>
                        @else
                            <p class="text-ys-s text-dc-secondary">Комментариев нет.</p>
                        @endif
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-surface rounded-md shadow-dc-card p-5">
                        <h3 class="font-semibold text-ys-m text-dc mb-3">Ссылки</h3>
                        <div class="space-y-2 text-ys-s">
                            <div>
                                <div class="text-dc-secondary text-ys-xs">Официальная ссылка</div>
                                @if($law->official_url)
                                    <a href="{{ $law->official_url }}" target="_blank" class="text-dc-primary hover:underline break-all">{{ $law->official_url }}</a>
                                @else
                                    <span class="text-dc-secondary">—</span>
                                @endif
                            </div>
                            <div>
                                <div class="text-dc-secondary text-ys-xs">Word версия</div>
                                @if($law->word_url)
                                    <a href="{{ $law->word_url }}" target="_blank" class="text-dc-primary hover:underline break-all">{{ $law->word_url }}</a>
                                @else
                                    <span class="text-dc-secondary">—</span>
                                @endif
                            </div>
                            <div>
                                <div class="text-dc-secondary text-ys-xs">Консультант+</div>
                                @if($law->consultant_url)
                                    <a href="{{ $law->consultant_url }}" target="_blank" class="text-dc-primary hover:underline break-all">{{ $law->consultant_url }}</a>
                                @else
                                    <span class="text-dc-secondary">—</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="bg-surface rounded-md shadow-dc-card p-5">
                        <h3 class="font-semibold text-ys-m text-dc mb-3">Связи</h3>
                        <div class="text-ys-s text-dc-secondary space-y-1">
                            <div>Шаблонов: <span class="text-dc">{{ $law->templates->count() }}</span></div>
                            <div>Проектов: <span class="text-dc">{{ $law->projects->count() }}</span></div>
                            <div>Экспертиз: <span class="text-dc">{{ $law->expertOpinions->count() }}</span></div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($activeTab === 'templates')
            <div class="bg-surface rounded-md shadow-dc-card p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-ys-m text-dc">Шаблоны ({{ $law->templates->count() }})</h3>
                    <a href="{{ route('templates.create') }}"
                       class="px-4 py-2 bg-dc-primary text-white rounded-2xs text-ys-s font-medium hover:bg-dc-blue-200 dc-transition">
                        + Шаблон
                    </a>
                </div>

                @if($law->templates->isEmpty())
                    <p class="text-dc-secondary text-ys-s">Нет шаблонов.</p>
                @else
                    <table class="w-full text-ys-s">
                        <thead>
                        <tr class="text-dc-secondary border-b border-dc">
                            <th class="pb-2 text-left font-medium">Категория</th>
                            <th class="pb-2 text-left font-medium">Название</th>
                            <th class="pb-2 text-left font-medium">Тип</th>
                            <th class="pb-2 text-left font-medium">Статус</th>
                            <th class="pb-2"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($law->templates as $tpl)
                            <tr class="dc-table-row border-b border-dc last:border-0">
                                <td class="dc-table-cell py-2 text-dc-secondary">{{ $tpl->category }}</td>
                                <td class="dc-table-cell py-2">
                                    <a href="{{ route('templates.show', $tpl) }}" class="text-dc-primary hover:underline">{{ $tpl->title }}</a>
                                </td>
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
        @elseif($activeTab === 'expertise')
            <div class="bg-surface rounded-md shadow-dc-card p-5 space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-ys-m text-dc">Экспертиза ({{ $law->expertOpinions->count() }})</h3>
                    <a href="{{ route('laws.edit', $law) }}#expertise"
                       class="px-4 py-2 text-dc-primary border border-dc rounded-2xs text-ys-s hover:bg-surface-hover dc-transition">
                        Редактировать
                    </a>
                </div>

                @if($law->expertOpinions->isEmpty())
                    <p class="text-dc-secondary text-ys-s">Пока нет мнений экспертов.</p>
                @else
                    <div class="space-y-3">
                        @foreach($law->expertOpinions as $i => $op)
                            <details class="rounded-2xs border border-dc p-4 bg-surface-2" @if($i===0) open @endif>
                                <summary class="cursor-pointer select-none flex items-center justify-between gap-4">
                                    <div class="text-ys-s text-dc font-medium">
                                        Мнение эксперта #{{ $i+1 }}
                                        @if($op->expert_name)
                                            — <span class="text-dc-secondary">{{ $op->expert_name }}</span>
                                        @endif
                                    </div>
                                    <div class="text-ys-xs text-dc-secondary">Развернуть/свернуть</div>
                                </summary>

                                <div class="mt-4 space-y-3">
                                    @if($op->opinion)
                                        <div>
                                            <div class="text-ys-xs text-dc-secondary mb-1">Мнение</div>
                                            <div class="text-ys-s text-dc whitespace-pre-wrap">{{ $op->opinion }}</div>
                                        </div>
                                    @endif

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-ys-s">
                                        <div>
                                            <div class="text-ys-xs text-dc-secondary mb-1">Видео</div>
                                            @if($op->video_url)
                                                <a href="{{ $op->video_url }}" class="text-dc-primary hover:underline break-all" target="_blank">{{ $op->video_url }}</a>
                                            @else
                                                <span class="text-dc-secondary">—</span>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-ys-xs text-dc-secondary mb-1">Ссылка на ресурс</div>
                                            @if($op->resource_url)
                                                <a href="{{ $op->resource_url }}" class="text-dc-primary hover:underline break-all" target="_blank">{{ $op->resource_url }}</a>
                                            @else
                                                <span class="text-dc-secondary">—</span>
                                            @endif
                                        </div>
                                    </div>

                                    @if($op->video_transcript)
                                        <div>
                                            <div class="text-ys-xs text-dc-secondary mb-1">Расшифровка видео</div>
                                            <div class="text-ys-s text-dc whitespace-pre-wrap">{{ $op->video_transcript }}</div>
                                        </div>
                                    @endif

                                    @if($op->file_path)
                                        <div>
                                            <div class="text-ys-xs text-dc-secondary mb-1">Файл (путь)</div>
                                            <div class="text-ys-s text-dc-secondary break-all">{{ $op->file_path }}</div>
                                        </div>
                                    @endif
                                </div>
                            </details>
                        @endforeach
                    </div>
                @endif
            </div>
        @elseif($activeTab === 'articles')
            <div class="bg-surface rounded-md shadow-dc-card p-5">
                <h3 class="font-semibold text-ys-m text-dc mb-2">Статьи</h3>
                <p class="text-ys-s text-dc-secondary">
                    План: хранить в БД (отдельная сущность). В Этапе 2 добавим модели/миграции/CRUD.
                </p>
            </div>
        @elseif($activeTab === 'documents')
            <div class="bg-surface rounded-md shadow-dc-card p-5">
                <h3 class="font-semibold text-ys-m text-dc mb-2">Документы</h3>
                <p class="text-ys-s text-dc-secondary">
                    План: хранить в БД + файлы. В Этапе 2 добавим модели/миграции/загрузку/связь с законом.
                </p>
            </div>
        @endif
    </div>
</x-app-layout>
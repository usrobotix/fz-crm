<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-ys-l text-dc">Редактировать закон</h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-surface rounded-md shadow-dc-card p-6">
            <form method="POST" action="{{ route('laws.update', $law) }}" class="space-y-6" id="law-form">
                @csrf
                @method('PUT')

                {{-- BASE FIELDS --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-ys-s font-medium text-dc mb-1">Код *</label>
                        <input type="text" name="code" value="{{ old('code', $law->code) }}" required
                               class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">
                        @error('code')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-ys-s font-medium text-dc mb-1">Статус *</label>
                        <select name="status"
                                class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">
                            @foreach(['draft','active','archived'] as $s)
                                <option value="{{ $s }}" @selected(old('status', $law->status ?? 'active')===$s)>{{ $s }}</option>
                            @endforeach
                        </select>
                        @error('status')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-ys-s font-medium text-dc mb-1">Название *</label>
                    <input type="text" name="name" value="{{ old('name', $law->name) }}" required
                           class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">
                    @error('name')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-ys-s font-medium text-dc mb-1">Страна</label>
                        <input type="text" name="country" value="{{ old('country', $law->country) }}"
                               class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">
                        @error('country')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-ys-s font-medium text-dc mb-1">Тип</label>
                        <input type="text" name="type" value="{{ old('type', $law->type) }}"
                               class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">
                        @error('type')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-ys-s font-medium text-dc mb-1">Дата</label>
                        <input type="date" name="published_at" value="{{ old('published_at', optional($law->published_at)->format('Y-m-d')) }}"
                               class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">
                        @error('published_at')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-ys-s font-medium text-dc mb-1">Официальная ссылка</label>
                        <input type="url" name="official_url" value="{{ old('official_url', $law->official_url) }}"
                               class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">
                        @error('official_url')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-ys-s font-medium text-dc mb-1">Word версия (ссылка)</label>
                        <input type="url" name="word_url" value="{{ old('word_url', $law->word_url) }}"
                               class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">
                        @error('word_url')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-ys-s font-medium text-dc mb-1">Консультант+</label>
                        <input type="url" name="consultant_url" value="{{ old('consultant_url', $law->consultant_url) }}"
                               class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">
                        @error('consultant_url')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-ys-s font-medium text-dc mb-1">Теги (через запятую)</label>
                    <input type="text" name="tags"
                           value="{{ old('tags', is_array($law->tags) ? implode(', ', $law->tags) : '') }}"
                           placeholder="персональные данные, GDPR, штрафы"
                           class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">
                    @error('tags')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-ys-s font-medium text-dc mb-1">Описание</label>
                    <textarea name="description" rows="4"
                              class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">{{ old('description', $law->description) }}</textarea>
                    @error('description')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-ys-s font-medium text-dc mb-1">Комментарии</label>
                    <textarea name="comment" rows="4"
                              class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">{{ old('comment', $law->comment) }}</textarea>
                    @error('comment')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- EXPERTISE --}}
                <div id="expertise" class="pt-2">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-semibold text-ys-m text-dc">Экспертиза</h3>
                        <button type="button" id="add-expertise"
                                class="px-4 py-2 bg-dc-primary text-white rounded-2xs text-ys-s font-medium hover:bg-dc-blue-200 dc-transition">
                            + Добавить мнение
                        </button>
                    </div>

                    <p class="text-ys-xs text-dc-secondary mb-3">
                        Каждый блок — мнение одного эксперта. Блоки можно сворачивать/разворачивать.
                    </p>

                    <div id="expertise-list" class="space-y-3">
                        @php
                            $oldExpertise = old('expertise');
                            $items = is_array($oldExpertise)
                                ? $oldExpertise
                                : $law->expertOpinions->map(fn($o) => [
                                    'id' => $o->id,
                                    'expert_name' => $o->expert_name,
                                    'opinion' => $o->opinion,
                                    'video_url' => $o->video_url,
                                    'video_transcript' => $o->video_transcript,
                                    'file_path' => $o->file_path,
                                    'resource_url' => $o->resource_url,
                                ])->toArray();
                        @endphp

                        @foreach($items as $i => $row)
                            <details class="rounded-2xs border border-dc p-4 bg-surface-2 expertise-item" open>
                                <summary class="cursor-pointer select-none flex items-center justify-between gap-4">
                                    <div class="text-ys-s text-dc font-medium">
                                        Мнение эксперта
                                        <span class="text-dc-secondary">(№ <span class="expertise-index">{{ $i+1 }}</span>)</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <button type="button"
                                                class="remove-expertise text-dc-red-100 text-ys-xs hover:underline">
                                            Удалить
                                        </button>
                                        <span class="text-ys-xs text-dc-secondary">Развернуть/свернуть</span>
                                    </div>
                                </summary>

                                <div class="mt-4 space-y-3">
                                    <input type="hidden" name="expertise[{{ $i }}][id]" value="{{ $row['id'] ?? '' }}">

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-ys-xs text-dc-secondary mb-1">Эксперт (имя)</label>
                                            <input type="text" name="expertise[{{ $i }}][expert_name]" value="{{ $row['expert_name'] ?? '' }}"
                                                   class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">
                                        </div>
                                        <div>
                                            <label class="block text-ys-xs text-dc-secondary mb-1">Видео (ссылка)</label>
                                            <input type="url" name="expertise[{{ $i }}][video_url]" value="{{ $row['video_url'] ?? '' }}"
                                                   class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-ys-xs text-dc-secondary mb-1">Мнение (текст)</label>
                                        <textarea name="expertise[{{ $i }}][opinion]" rows="5"
                                                  class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">{{ $row['opinion'] ?? '' }}</textarea>
                                    </div>

                                    <div>
                                        <label class="block text-ys-xs text-dc-secondary mb-1">Расшифровка видео</label>
                                        <textarea name="expertise[{{ $i }}][video_transcript]" rows="4"
                                                  class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">{{ $row['video_transcript'] ?? '' }}</textarea>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-ys-xs text-dc-secondary mb-1">Файл (путь/URL — пока строкой)</label>
                                            <input type="text" name="expertise[{{ $i }}][file_path]" value="{{ $row['file_path'] ?? '' }}"
                                                   class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">
                                        </div>
                                        <div>
                                            <label class="block text-ys-xs text-dc-secondary mb-1">Ссылка на ресурс</label>
                                            <input type="url" name="expertise[{{ $i }}][resource_url]" value="{{ $row['resource_url'] ?? '' }}"
                                                   class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">
                                        </div>
                                    </div>
                                </div>
                            </details>
                        @endforeach
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="px-5 py-2 bg-dc-primary text-white rounded-2xs text-ys-s font-medium hover:bg-dc-blue-200 dc-transition">
                        Сохранить
                    </button>
                    <a href="{{ route('laws.show', $law) }}"
                       class="px-5 py-2 text-dc-secondary hover:text-dc text-ys-s dc-transition">
                        Отмена
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            (function () {
                const list = document.getElementById('expertise-list');
                const addBtn = document.getElementById('add-expertise');

                function renumber() {
                    const items = list.querySelectorAll('.expertise-item');
                    items.forEach((item, idx) => {
                        const idxSpan = item.querySelector('.expertise-index');
                        if (idxSpan) idxSpan.textContent = String(idx + 1);

                        // Обновляем name="expertise[i][...]" на актуальный i
                        item.querySelectorAll('input[name], textarea[name], select[name]').forEach(el => {
                            el.name = el.name.replace(/expertise\[\d+\]/, 'expertise[' + idx + ']');
                        });
                    });
                }

                function makeItem(initial = {}) {
                    const wrapper = document.createElement('details');
                    wrapper.className = 'rounded-2xs border border-dc p-4 bg-surface-2 expertise-item';
                    wrapper.open = true;

                    const i = list.querySelectorAll('.expertise-item').length;

                    wrapper.innerHTML = `
                        <summary class="cursor-pointer select-none flex items-center justify-between gap-4">
                            <div class="text-ys-s text-dc font-medium">
                                Мнение эксперта
                                <span class="text-dc-secondary">(№ <span class="expertise-index">${i + 1}</span>)</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="button" class="remove-expertise text-dc-red-100 text-ys-xs hover:underline">Удалить</button>
                                <span class="text-ys-xs text-dc-secondary">Развернуть/свернуть</span>
                            </div>
                        </summary>

                        <div class="mt-4 space-y-3">
                            <input type="hidden" name="expertise[${i}][id]" value="${initial.id || ''}">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-ys-xs text-dc-secondary mb-1">Эксперт (имя)</label>
                                    <input type="text" name="expertise[${i}][expert_name]" value="${initial.expert_name || ''}"
                                        class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-ys-xs text-dc-secondary mb-1">Видео (ссылка)</label>
                                    <input type="url" name="expertise[${i}][video_url]" value="${initial.video_url || ''}"
                                        class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">
                                </div>
                            </div>

                            <div>
                                <label class="block text-ys-xs text-dc-secondary mb-1">Мнение (текст)</label>
                                <textarea name="expertise[${i}][opinion]" rows="5"
                                    class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">${initial.opinion || ''}</textarea>
                            </div>

                            <div>
                                <label class="block text-ys-xs text-dc-secondary mb-1">Расшифровка видео</label>
                                <textarea name="expertise[${i}][video_transcript]" rows="4"
                                    class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">${initial.video_transcript || ''}</textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-ys-xs text-dc-secondary mb-1">Файл (путь/URL — пока строкой)</label>
                                    <input type="text" name="expertise[${i}][file_path]" value="${initial.file_path || ''}"
                                        class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-ys-xs text-dc-secondary mb-1">Ссылка на ресурс</label>
                                    <input type="url" name="expertise[${i}][resource_url]" value="${initial.resource_url || ''}"
                                        class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">
                                </div>
                            </div>
                        </div>
                    `;

                    return wrapper;
                }

                list.addEventListener('click', (e) => {
                    const btn = e.target.closest('.remove-expertise');
                    if (!btn) return;

                    e.preventDefault();
                    const item = e.target.closest('.expertise-item');
                    if (item) {
                        item.remove();
                        renumber();
                    }
                });

                addBtn.addEventListener('click', () => {
                    list.appendChild(makeItem());
                    renumber();
                });

                // на всякий случай, если была загрузка старых данных
                renumber();
            })();
        </script>
    @endpush
</x-app-layout>
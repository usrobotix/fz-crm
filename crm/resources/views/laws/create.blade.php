<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-ys-l text-dc">Новый закон</h2></x-slot>

    <div class="py-8 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-surface rounded-md shadow-dc-card p-6">
            <form method="POST" action="{{ route('laws.store') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-ys-s font-medium text-dc mb-1">Код *</label>
                        <input type="text" name="code" value="{{ old('code') }}" placeholder="FZ-152" required
                               class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">
                        @error('code')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-ys-s font-medium text-dc mb-1">Статус *</label>
                        <select name="status"
                                class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">
                            @foreach(['draft','active','archived'] as $s)
                                <option value="{{ $s }}" @selected(old('status','active')===$s)>{{ $s }}</option>
                            @endforeach
                        </select>
                        @error('status')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-ys-s font-medium text-dc mb-1">Название *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">
                    @error('name')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-ys-s font-medium text-dc mb-1">Страна</label>
                        <input type="text" name="country" value="{{ old('country','RU') }}"
                               class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-ys-s font-medium text-dc mb-1">Тип</label>
                        <input type="text" name="type" value="{{ old('type','law') }}"
                               class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-ys-s font-medium text-dc mb-1">Дата</label>
                        <input type="date" name="published_at" value="{{ old('published_at') }}"
                               class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-ys-s font-medium text-dc mb-1">Официальная ссылка</label>
                        <input type="url" name="official_url" value="{{ old('official_url') }}"
                               class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-ys-s font-medium text-dc mb-1">Word версия (ссылка)</label>
                        <input type="url" name="word_url" value="{{ old('word_url') }}"
                               class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-ys-s font-medium text-dc mb-1">Консультант+</label>
                        <input type="url" name="consultant_url" value="{{ old('consultant_url') }}"
                               class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">
                    </div>
                </div>

                <div>
                    <label class="block text-ys-s font-medium text-dc mb-1">Теги (через запятую)</label>
                    <input type="text" name="tags" value="{{ old('tags') }}" placeholder="персональные данные, GDPR, штрафы"
                           class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">
                </div>

                <div>
                    <label class="block text-ys-s font-medium text-dc mb-1">Описание</label>
                    <textarea name="description" rows="4"
                              class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="block text-ys-s font-medium text-dc mb-1">Комментарии</label>
                    <textarea name="comment" rows="4"
                              class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2">{{ old('comment') }}</textarea>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="px-5 py-2 bg-dc-primary text-white rounded-2xs text-ys-s font-medium hover:bg-dc-blue-200 dc-transition">Сохранить</button>
                    <a href="{{ route('laws.index') }}" class="px-5 py-2 text-dc-secondary hover:text-dc text-ys-s dc-transition">Отмена</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
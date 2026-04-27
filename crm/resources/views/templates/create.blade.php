<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-ys-l text-dc">Новый шаблон</h2></x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('templates.store') }}" class="space-y-6" id="template-create-form">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Metadata --}}
                <div class="bg-surface rounded-md shadow-dc-card p-5 space-y-4">
                    <h3 class="font-semibold text-ys-m text-dc border-b border-dc pb-3">Метаданные</h3>

                    <div>
                        <label class="block text-ys-s font-medium text-dc mb-1">
                            Закон <span class="text-dc-red-100">*</span>
                        </label>
                        <select name="law_id" required
                                class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2 focus:outline-none focus:border-dc-blue-100">
                            <option value="">— Выберите закон —</option>
                            @foreach($laws as $law)
                                <option value="{{ $law->id }}" {{ old('law_id') == $law->id ? 'selected' : '' }}>
                                    {{ $law->code }} — {{ $law->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('law_id')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-ys-s font-medium text-dc mb-1">
                            Название <span class="text-dc-red-100">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2 focus:outline-none focus:border-dc-blue-100">
                        @error('title')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-ys-s font-medium text-dc mb-1">Категория</label>
                        <input type="text" name="category" value="{{ old('category') }}" placeholder="Например: Приказы"
                               class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2 focus:outline-none focus:border-dc-blue-100">
                    </div>

                    <div>
                        <label class="block text-ys-s font-medium text-dc mb-1">Тип документа</label>
                        <input type="text" name="doc_type" value="{{ old('doc_type') }}" placeholder="Например: Политика"
                               class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2 focus:outline-none focus:border-dc-blue-100">
                    </div>

                    <div>
                        <label class="block text-ys-s font-medium text-dc mb-1">Источник</label>
                        <input type="text" name="source" value="{{ old('source') }}"
                               class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2 focus:outline-none focus:border-dc-blue-100">
                    </div>

                    <div>
                        <label class="block text-ys-s font-medium text-dc mb-1">Статус</label>
                        <select name="status"
                                class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2 focus:outline-none focus:border-dc-blue-100">
                            <option value="draft" {{ old('status','draft') === 'draft' ? 'selected' : '' }}>Черновик</option>
                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Активный</option>
                            <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Устаревший</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-ys-s font-medium text-dc mb-1">Путь в репозитории</label>
                        <input type="text" name="repo_path" value="{{ old('repo_path') }}" placeholder="docs/templates/..."
                               class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2 focus:outline-none focus:border-dc-blue-100 font-mono text-ys-xs">
                    </div>

                    <div>
                        <label class="block text-ys-s font-medium text-dc mb-1">Комментарий к версии</label>
                        <input type="text" name="comment" value="{{ old('comment') }}" placeholder="Начальная версия"
                               class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2 focus:outline-none focus:border-dc-blue-100">
                    </div>

                    <div class="pt-2 flex gap-3">
                        <button type="submit"
                                class="px-5 py-2 bg-dc-primary text-white rounded-2xs text-ys-s font-medium hover:bg-dc-blue-200 dc-transition">
                            Сохранить
                        </button>
                        <a href="{{ route('templates.index') }}"
                           class="px-5 py-2 text-dc-secondary hover:text-dc text-ys-s dc-transition">
                            Отмена
                        </a>
                    </div>
                </div>

                {{-- Markdown editor + preview --}}
                <div class="lg:col-span-2 bg-surface rounded-md shadow-dc-card overflow-hidden flex flex-col" style="min-height:600px">
                    <div class="flex border-b border-dc">
                        <button type="button" id="tab-editor"
                                class="px-4 py-3 text-ys-s font-medium text-dc-primary border-b-2 border-dc-blue-100">
                            Редактор
                        </button>
                        <button type="button" id="tab-preview"
                                class="px-4 py-3 text-ys-s font-medium text-dc-secondary hover:text-dc">
                            Предпросмотр
                        </button>
                    </div>

                    <div class="flex flex-1 overflow-hidden">
                        <textarea id="body-input" name="body"
                                  placeholder="# Название документа&#10;&#10;Содержимое в формате Markdown..."
                                  class="flex-1 p-4 text-ys-s font-mono bg-surface text-dc resize-none focus:outline-none border-0"
                                  style="min-height:540px">{{ old('body') }}</textarea>

                        <div id="preview-pane"
                             class="hidden flex-1 p-4 overflow-y-auto prose prose-sm max-w-none text-dc"
                             style="min-height:540px"></div>
                    </div>

                    {{-- Показываем ошибку body, даже если вывод ошибок не предусмотрен в layout --}}
                    @error('body')
                        <div class="px-4 py-3 border-t border-dc text-dc-red-100 text-ys-xs">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </form>
    </div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const editor = document.getElementById('body-input');
    const preview = document.getElementById('preview-pane');
    const tabEditor = document.getElementById('tab-editor');
    const tabPreview = document.getElementById('tab-preview');

    function setActiveTab(tab) {
        if (tab === 'editor') {
            editor.classList.remove('hidden');
            preview.classList.add('hidden');

            tabEditor.classList.add('text-dc-primary', 'border-b-2', 'border-dc-blue-100');
            tabEditor.classList.remove('text-dc-secondary');

            tabPreview.classList.remove('text-dc-primary', 'border-b-2', 'border-dc-blue-100');
            tabPreview.classList.add('text-dc-secondary');
        } else {
            editor.classList.add('hidden');
            preview.classList.remove('hidden');

            if (typeof marked !== 'undefined') {
                preview.innerHTML = marked.parse(editor.value || '');
            } else {
                preview.innerHTML = '<p style="color:#b00">Ошибка: marked не загрузился</p>';
            }

            tabPreview.classList.add('text-dc-primary', 'border-b-2', 'border-dc-blue-100');
            tabPreview.classList.remove('text-dc-secondary');

            tabEditor.classList.remove('text-dc-primary', 'border-b-2', 'border-dc-blue-100');
            tabEditor.classList.add('text-dc-secondary');
        }
    }

    setActiveTab('editor');

    tabEditor.addEventListener('click', function () { setActiveTab('editor'); });
    tabPreview.addEventListener('click', function () { setActiveTab('preview'); });

    editor.addEventListener('input', function () {
        if (!preview.classList.contains('hidden') && typeof marked !== 'undefined') {
            preview.innerHTML = marked.parse(editor.value || '');
        }
    });
});
</script>
@endpush
</x-app-layout>
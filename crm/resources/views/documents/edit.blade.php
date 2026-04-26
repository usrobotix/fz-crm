<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('projects.documents.show', [$project, $document]) }}" class="text-dc-secondary hover:text-dc text-ys-s">← {{ $document->title }}</a>
            <h2 class="font-semibold text-ys-l text-dc">Редактировать документ</h2>
        </div>
    </x-slot>
    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('projects.documents.update', [$project, $document]) }}" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Metadata --}}
                <div class="bg-surface rounded-md shadow-dc-card p-5 space-y-4">
                    <h3 class="font-semibold text-ys-m text-dc border-b border-dc pb-3">Параметры</h3>
                    <div>
                        <label class="block text-ys-s font-medium text-dc mb-1">Название <span class="text-dc-red-100">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $document->title) }}" required
                            class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2 focus:outline-none focus:border-dc-blue-100">
                        @error('title')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-ys-s font-medium text-dc mb-1">Статус</label>
                        <select name="status"
                            class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2 focus:outline-none focus:border-dc-blue-100">
                            @foreach(['draft'=>'Черновик','in_progress'=>'В работе','review'=>'На проверке','approved'=>'Одобрен','final'=>'Финальный','obsolete'=>'Устарел'] as $val => $lbl)
                            <option value="{{ $val }}" {{ old('status', $document->status) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-ys-s font-medium text-dc mb-1">Тип документа</label>
                        <input type="text" name="doc_type" value="{{ old('doc_type', $document->doc_type) }}"
                            class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2 focus:outline-none focus:border-dc-blue-100">
                    </div>
                    <div>
                        <label class="block text-ys-s font-medium text-dc mb-1">Исполнитель</label>
                        <select name="assigned_to"
                            class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2 focus:outline-none focus:border-dc-blue-100">
                            <option value="">— Не назначен —</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to', $document->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-ys-s font-medium text-dc mb-1">Причина изменения <span class="text-dc-red-100">*</span></label>
                        <input type="text" name="change_note" value="{{ old('change_note') }}" required placeholder="Что изменено..."
                            class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2 focus:outline-none focus:border-dc-blue-100">
                        @error('change_note')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="pt-2 flex gap-3">
                        <button type="submit" class="px-5 py-2 bg-dc-primary text-white rounded-2xs text-ys-s font-medium hover:bg-dc-blue-200 dc-transition">Сохранить</button>
                        <a href="{{ route('projects.documents.show', [$project, $document]) }}" class="px-5 py-2 text-dc-secondary hover:text-dc text-ys-s dc-transition">Отмена</a>
                    </div>
                </div>

                {{-- Markdown editor --}}
                <div class="lg:col-span-2 bg-surface rounded-md shadow-dc-card overflow-hidden flex flex-col" style="min-height:500px">
                    <div class="flex border-b border-dc">
                        <button type="button" onclick="switchTab('editor')" id="tab-editor"
                            class="px-4 py-3 text-ys-s font-medium text-dc-primary border-b-2 border-dc-blue-100">Редактор</button>
                        <button type="button" onclick="switchTab('preview')" id="tab-preview"
                            class="px-4 py-3 text-ys-s font-medium text-dc-secondary hover:text-dc">Предпросмотр</button>
                    </div>
                    <div class="flex flex-1 overflow-hidden">
                        <textarea id="body-input" name="body"
                            class="flex-1 p-4 text-ys-s font-mono bg-surface text-dc resize-none focus:outline-none border-0"
                            style="min-height:450px">{{ old('body', $document->latestVersion?->body) }}</textarea>
                        <div id="preview-pane" class="hidden flex-1 p-4 overflow-y-auto prose prose-sm max-w-none text-dc" style="min-height:450px"></div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
function switchTab(tab) {
    const editor = document.getElementById('body-input');
    const preview = document.getElementById('preview-pane');
    const tabEditor = document.getElementById('tab-editor');
    const tabPreview = document.getElementById('tab-preview');
    if (tab === 'editor') {
        editor.classList.remove('hidden'); preview.classList.add('hidden');
        tabEditor.classList.add('text-dc-primary','border-b-2','border-dc-blue-100');
        tabEditor.classList.remove('text-dc-secondary');
        tabPreview.classList.remove('text-dc-primary','border-b-2','border-dc-blue-100');
        tabPreview.classList.add('text-dc-secondary');
    } else {
        editor.classList.add('hidden'); preview.classList.remove('hidden');
        preview.innerHTML = marked.parse(editor.value || '');
        tabPreview.classList.add('text-dc-primary','border-b-2','border-dc-blue-100');
        tabPreview.classList.remove('text-dc-secondary');
        tabEditor.classList.remove('text-dc-primary','border-b-2','border-dc-blue-100');
        tabEditor.classList.add('text-dc-secondary');
    }
}
document.getElementById('body-input').addEventListener('input', function() {
    const preview = document.getElementById('preview-pane');
    if (!preview.classList.contains('hidden')) preview.innerHTML = marked.parse(this.value || '');
});
</script>
@endpush

<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-ys-l text-dc">Новый проект</h2></x-slot>
    <div class="py-8 max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-surface rounded-md shadow-dc-card p-6">
            <form method="POST" action="{{ route('projects.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-ys-s font-medium text-dc mb-1">Компания <span class="text-dc-red-100">*</span></label>
                    <select name="company_id" required
                        class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2 focus:outline-none focus:border-dc-blue-100">
                        <option value="">— Выберите компанию —</option>
                        @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                        @endforeach
                    </select>
                    @error('company_id')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-ys-s font-medium text-dc mb-1">Закон / Фреймворк</label>
                    <select name="law_id"
                        class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2 focus:outline-none focus:border-dc-blue-100">
                        <option value="">— Не выбран —</option>
                        @foreach($laws as $law)
                        <option value="{{ $law->id }}" {{ old('law_id') == $law->id ? 'selected' : '' }}>{{ $law->code }} — {{ $law->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-ys-s font-medium text-dc mb-1">Название <span class="text-dc-red-100">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2 focus:outline-none focus:border-dc-blue-100">
                    @error('name')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-ys-s font-medium text-dc mb-1">Дедлайн</label>
                    <input type="date" name="due_at" value="{{ old('due_at') }}"
                        class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2 focus:outline-none focus:border-dc-blue-100">
                </div>
                <div>
                    <label class="block text-ys-s font-medium text-dc mb-1">Статус</label>
                    <select name="status"
                        class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2 focus:outline-none focus:border-dc-blue-100">
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Активный</option>
                        <option value="paused" {{ old('status') === 'paused' ? 'selected' : '' }}>Приостановлен</option>
                        <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Завершён</option>
                        <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Архив</option>
                    </select>
                </div>
                <div>
                    <label class="block text-ys-s font-medium text-dc mb-1">Примечания</label>
                    <textarea name="notes" rows="3"
                        class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2 focus:outline-none focus:border-dc-blue-100">{{ old('notes') }}</textarea>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="px-5 py-2 bg-dc-primary text-white rounded-2xs text-ys-s font-medium hover:bg-dc-blue-200 dc-transition">Создать</button>
                    <a href="{{ route('projects.index') }}" class="px-5 py-2 text-dc-secondary hover:text-dc text-ys-s dc-transition">Отмена</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-ys-l text-dc">Новый закон</h2></x-slot>
    <div class="py-8 max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-surface rounded-md shadow-dc-card p-6">
            <form method="POST" action="{{ route('laws.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-ys-s font-medium text-dc mb-1">Код <span class="text-dc-red-100">*</span></label>
                    <input type="text" name="code" value="{{ old('code') }}" placeholder="fz152" required
                        class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2 focus:outline-none focus:border-dc-blue-100">
                    @error('code')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-ys-s font-medium text-dc mb-1">Название <span class="text-dc-red-100">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2 focus:outline-none focus:border-dc-blue-100">
                    @error('name')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-ys-s font-medium text-dc mb-1">Описание</label>
                    <textarea name="description" rows="3"
                        class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2 focus:outline-none focus:border-dc-blue-100">{{ old('description') }}</textarea>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="px-5 py-2 bg-dc-primary text-white rounded-2xs text-ys-s font-medium hover:bg-dc-blue-200 dc-transition">Сохранить</button>
                    <a href="{{ route('laws.index') }}" class="px-5 py-2 text-dc-secondary hover:text-dc text-ys-s dc-transition">Отмена</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

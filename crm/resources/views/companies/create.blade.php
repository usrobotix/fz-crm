<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-ys-l text-dc">Новая компания</h2></x-slot>
    <div class="py-8 max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-surface rounded-md shadow-dc-card p-6">
            <form method="POST" action="{{ route('companies.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-ys-s font-medium text-dc mb-1">Название <span class="text-dc-red-100">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2 focus:outline-none focus:border-dc-blue-100">
                    @error('name')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-ys-s font-medium text-dc mb-1">ИНН</label>
                    <input type="text" name="inn" value="{{ old('inn') }}" placeholder="1234567890"
                        class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2 focus:outline-none focus:border-dc-blue-100 font-mono">
                    @error('inn')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-ys-s font-medium text-dc mb-1">Контактное лицо</label>
                    <input type="text" name="contact_person" value="{{ old('contact_person') }}"
                        class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2 focus:outline-none focus:border-dc-blue-100">
                </div>
                <div>
                    <label class="block text-ys-s font-medium text-dc mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2 focus:outline-none focus:border-dc-blue-100">
                    @error('email')<p class="text-dc-red-100 text-ys-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-ys-s font-medium text-dc mb-1">Телефон</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+7 (999) 000-00-00"
                        class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2 focus:outline-none focus:border-dc-blue-100">
                </div>
                <div>
                    <label class="block text-ys-s font-medium text-dc mb-1">Примечания</label>
                    <textarea name="notes" rows="3"
                        class="w-full rounded-2xs border border-dc-gray-20 bg-surface text-dc text-ys-s px-3 py-2 focus:outline-none focus:border-dc-blue-100">{{ old('notes') }}</textarea>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="px-5 py-2 bg-dc-primary text-white rounded-2xs text-ys-s font-medium hover:bg-dc-blue-200 dc-transition">Сохранить</button>
                    <a href="{{ route('companies.index') }}" class="px-5 py-2 text-dc-secondary hover:text-dc text-ys-s dc-transition">Отмена</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

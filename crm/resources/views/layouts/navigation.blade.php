<nav x-data="{ open: false }" style="background-color:var(--color-surface);border-bottom:1px solid var(--color-border)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="font-bold text-ys-m text-dc-primary">ФЗ-CRM</a>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Главная</x-nav-link>
                    <x-nav-link :href="route('laws.index')" :active="request()->routeIs('laws.*')">Законы</x-nav-link>
                    <x-nav-link :href="route('templates.index')" :active="request()->routeIs('templates.*')">Шаблоны</x-nav-link>
                    <x-nav-link :href="route('companies.index')" :active="request()->routeIs('companies.*')">Компании</x-nav-link>
                    <x-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.*')">Проекты</x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-2">
                <button onclick="toggleTheme()" class="p-2 rounded-md text-dc-secondary hover:bg-surface-hover dc-transition focus:outline-none" aria-label="Тема">
                    <svg id="theme-icon-sun" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 5a7 7 0 100 14 7 7 0 000-14z"/></svg>
                    <svg id="theme-icon-moon" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3a7 7 0 109.79 9.79z"/></svg>
                </button>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-ys-s font-medium rounded-2xs text-dc-secondary hover:text-dc focus:outline-none dc-transition">
                            {{ Auth::user()->name }}
                            <svg class="ms-1 fill-current h-4 w-4" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">Профиль</x-dropdown-link>
                        @if(auth()->user()->hasRole('admin'))
                        <x-dropdown-link :href="route('admin.technical.backups.index')">⚙️ Технический раздел</x-dropdown-link>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();this.closest('form').submit();">Выйти</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-dc-secondary hover:text-dc hover:bg-surface-hover dc-transition focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden':open,'inline-flex':!open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden':!open,'inline-flex':open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block':open,'hidden':!open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Главная</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('laws.index')" :active="request()->routeIs('laws.*')">Законы</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('templates.index')" :active="request()->routeIs('templates.*')">Шаблоны</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('companies.index')" :active="request()->routeIs('companies.*')">Компании</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.*')">Проекты</x-responsive-nav-link>
        </div>
        <div class="pt-4 pb-1 border-t border-dc">
            <div class="px-4">
                <div class="font-medium text-ys-s text-dc">{{ Auth::user()->name }}</div>
                <div class="font-medium text-ys-xs text-dc-secondary">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">Профиль</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();this.closest('form').submit();">Выйти</x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
function toggleTheme(){
    const html=document.documentElement;
    const isDark=html.classList.toggle('dark');
    localStorage.setItem('theme',isDark?'dark':'light');
    updateThemeIcons(isDark);
}
function updateThemeIcons(isDark){
    ['theme-icon-sun'].forEach(id=>{const el=document.getElementById(id);if(el)el.classList.toggle('hidden',isDark);});
    ['theme-icon-moon'].forEach(id=>{const el=document.getElementById(id);if(el)el.classList.toggle('hidden',!isDark);});
}
(function(){updateThemeIcons(document.documentElement.classList.contains('dark'));})();
</script>

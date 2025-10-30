<nav x-data="{ open: false }" class="bg-primary border-b border-primary-dark shadow-md sticky top-0 z-40">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <!-- Logo con Fondo Blanco -->
<div class="shrink-0 flex items-center">
    <a href="{{ route('dashboard') }}">
        {{-- El contenedor que crea la caja blanca --}}
        <div class="bg-white p-1 rounded-md shadow">
            <img src="{{ asset('img/EP.png') }}" alt="EP Consultora Logo" class="block h-9 w-auto">
        </div>
    </a>
</div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-nav-link>
                    <x-nav-link :href="route('clients.index')" :active="request()->routeIs('clients.*')">Clientes</x-nav-link>
                    <x-nav-link :href="route('deals.index')" :active="request()->routeIs('deals.*')">Pipeline</x-nav-link>
                    <x-nav-link :href="route('leads.index')" :active="request()->routeIs('leads.*')">Leads</x-nav-link>
                    <x-nav-link :href="route('sequences.index')" :active="request()->routeIs('sequences.*')">Secuencias</x-nav-link>
                    <x-nav-link :href="route('enrollments.index')" :active="request()->routeIs('enrollments.*')">Inscripciones</x-nav-link>
                    <x-nav-link :href="route('reports.sales')" :active="request()->routeIs('reports.sales')">Reportes</x-nav-link>
                    <x-nav-link :href="route('calendar.index')" :active="request()->routeIs('calendar.*')">Calendario</x-nav-link>
                    <x-nav-link :href="route('tasks.index')" :active="request()->routeIs('tasks.*')">Tareas</x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-200 hover:text-white bg-transparent focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">Mi Perfil</x-dropdown-link>
                        <x-dropdown-link :href="route('settings.index')">Configuración</x-dropdown-link>
                        <div class="border-t border-gray-200 dark:border-gray-600"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Cerrar Sesión</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-200 hover:text-white hover:bg-primary-dark focus:outline-none focus:bg-primary-dark transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /><path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-primary">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('clients.index')" :active="request()->routeIs('clients.*')">Clientes</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('deals.index')" :active="request()->routeIs('deals.*')">Pipeline</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('leads.index')" :active="request()->routeIs('leads.*')">Leads</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('sequences.index')" :active="request()->routeIs('sequences.*')">Secuencias</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('enrollments.index')" :active="request()->routeIs('enrollments.*')">Inscripciones</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('reports.sales')" :active="request()->routeIs('reports.sales')">Reportes</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('calendar.index')" :active="request()->routeIs('calendar.*')">Calendario</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('tasks.index')" :active="request()->routeIs('tasks.*')">Tareas</x-responsive-nav-link>
        </div>
        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-primary-dark">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-300">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">Mi Perfil</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('settings.index')">Configuración</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Cerrar Sesión</x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
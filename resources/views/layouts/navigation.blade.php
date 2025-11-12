<nav x-data="{ open: false }" class="bg-primary border-b border-primary-dark shadow-md sticky top-0 z-40">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <div class="bg-white p-1 rounded-md shadow">
                            <img src="{{ asset('img/EP.png') }}" alt="EP Consultora Logo" class="block h-9 w-auto">
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:-my-px sm:ms-10 sm:flex items-center space-x-4">
                    <!-- Dashboard -->
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="flex items-center">
                        <i class="fas fa-chart-line mr-2"></i>
                        <span>Dashboard</span>
                    </x-nav-link>

                    <!-- Clientes y Sedes Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center px-3 py-2 text-sm font-medium text-gray-200 hover:text-white transition" 
                                :class="{'text-white': {{ request()->routeIs('clients.*') || request()->routeIs('establishments.*') ? 'true' : 'false' }}}">
                            <i class="fas fa-building mr-2"></i>
                            <span>Clientes</span>
                            <i class="fas fa-chevron-down ml-2 text-xs"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" 
                             class="absolute z-50 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                            <div class="py-1">
                                <x-dropdown-link :href="route('clients.index')" :active="request()->routeIs('clients.*')">
                                    <i class="fas fa-users mr-2"></i> Todos los Clientes
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('establishments.indexAll')" :active="request()->routeIs('establishments.*')">
                                    <i class="fas fa-building mr-2"></i> Sedes
                                </x-dropdown-link>
                            </div>
                        </div>
                    </div>

                    <!-- Ventas Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center px-3 py-2 text-sm font-medium text-gray-200 hover:text-white transition"
                                :class="{'text-white': {{ request()->routeIs('deals.*') || request()->routeIs('leads.*') ? 'true' : 'false' }}}">
                            <i class="fas fa-dollar-sign mr-2"></i>
                            <span>Ventas</span>
                            <i class="fas fa-chevron-down ml-2 text-xs"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" 
                             class="absolute z-50 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                            <div class="py-1">
                                <x-dropdown-link :href="route('deals.index')" :active="request()->routeIs('deals.*')">
                                    <i class="fas fa-project-diagram mr-2"></i> Pipeline
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('leads.index')" :active="request()->routeIs('leads.*')">
                                    <i class="fas fa-funnel-dollar mr-2"></i> Leads
                                </x-dropdown-link>
                            </div>
                        </div>
                    </div>

                    <!-- Dropdown oculto en versión 1.0 estable -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center px-3 py-2 text-sm font-medium text-gray-200 hover:text-white transition"
                                :class="{'text-white': {{ request()->routeIs('sequences.*') || request()->routeIs('enrollments.*') ? 'true' : 'false' }}}">
                            <i class="fas fa-robot mr-2"></i>
                            {{-- <span>Automatización</span> --}} <!-- Oculto en versión 1.0 estable -->
                            <i class="fas fa-chevron-down ml-2 text-xs"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" 
                             class="absolute z-50 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                            <div class="py-1">
                                {{-- Enlaces de Secuencias e Inscripciones ocultos en versión 1.0 estable --}}
                                {{-- <x-dropdown-link :href="route('sequences.index')" :active="request()->routeIs('sequences.*')">
                                    <i class="fas fa-sitemap mr-2"></i> Secuencias
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('enrollments.index')" :active="request()->routeIs('enrollments.*')">
                                    <i class="fas fa-users-cog mr-2"></i> Inscripciones
                                </x-dropdown-link> --}}
                            </div>
                        </div>
                    </div>

                    <!-- Gestión Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center px-3 py-2 text-sm font-medium text-gray-200 hover:text-white transition"
                                :class="{'text-white': {{ request()->routeIs('calendar.*') || request()->routeIs('tasks.*') ? 'true' : 'false' }}}">
                            <i class="fas fa-tasks mr-2"></i>
                            <span>Gestión</span>
                            <i class="fas fa-chevron-down ml-2 text-xs"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" 
                             class="absolute z-50 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                            <div class="py-1">
                                <x-dropdown-link :href="route('calendar.index')" :active="request()->routeIs('calendar.*')">
                                    <i class="fas fa-calendar-alt mr-2"></i> Calendario
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('tasks.index')" :active="request()->routeIs('tasks.*')">
                                    <i class="fas fa-clipboard-list mr-2"></i> Tareas
                                </x-dropdown-link>
                            </div>
                        </div>
                    </div>

                    <!-- Reports -->
                    <x-nav-link :href="route('reports.sales')" :active="request()->routeIs('reports.sales')" class="flex items-center">
                        <i class="fas fa-chart-bar mr-2"></i>
                        <span>Reportes</span>
                    </x-nav-link>

                    <!-- Admin Section -->
                    @if(auth()->user()->is_admin)
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center px-3 py-2 text-sm font-medium text-gray-200 hover:text-white transition"
                                    :class="{'text-white': {{ request()->routeIs('admin.*') ? 'true' : 'false' }}}">
                                <i class="fas fa-shield-alt mr-2"></i>
                                <span>Administración</span>
                                <i class="fas fa-chevron-down ml-2 text-xs"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" 
                                 class="absolute z-50 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                <div class="py-1">
                                    <x-dropdown-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                        <i class="fas fa-tachometer-alt mr-2"></i> Dashboard Global
                                    </x-dropdown-link>
                                    
                                    <x-dropdown-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                                        <i class="fas fa-users-cog mr-2"></i> Gestión de Usuarios
                                    </x-dropdown-link>

                                    <x-dropdown-link :href="route('admin.performance.index')" :active="request()->routeIs('admin.performance.*')">
                                        <i class="fas fa-chart-line mr-2"></i> Métricas de Rendimiento
                                    </x-dropdown-link>

                                    <div class="border-t border-gray-100"></div>

                                    <x-dropdown-link :href="route('admin.settings.email')" :active="request()->routeIs('admin.settings.*')">
                                        <i class="fas fa-cogs mr-2"></i> Configuración Sistema
                                    </x-dropdown-link>

                                    <x-dropdown-link :href="route('admin.maintenance')" :active="request()->routeIs('admin.maintenance')">
                                        <i class="fas fa-tools mr-2"></i> Mantenimiento
                                    </x-dropdown-link>

                                    <x-dropdown-link :href="route('admin.logs')" :active="request()->routeIs('admin.logs')">
                                        <i class="fas fa-clipboard-list mr-2"></i> Logs del Sistema
                                    </x-dropdown-link>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-200 hover:text-white bg-primary-dark/40 hover:bg-primary-dark focus:outline-none transition ease-in-out duration-150">
                                <div class="flex items-center">
                                    <i class="fas fa-user-circle text-xl mr-2"></i>
                                    <span class="mr-1">{{ Auth::user()->name }}</span>
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="px-4 py-2 text-xs text-gray-400">
                                {{ Auth::user()->email }}
                            </div>
                            <x-dropdown-link :href="route('profile.edit')" class="flex items-center">
                                <i class="fas fa-user-edit w-4 mr-2"></i> Mi Perfil
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('settings.index')" class="flex items-center">
                                <i class="fas fa-cog w-4 mr-2"></i> Configuración
                            </x-dropdown-link>
                            <div class="border-t border-gray-200 dark:border-gray-600"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" 
                                               onclick="event.preventDefault(); this.closest('form').submit();"
                                               class="flex items-center text-red-600 hover:text-red-700 hover:bg-red-50">
                                    <i class="fas fa-sign-out-alt w-4 mr-2"></i> Cerrar Sesión
                                </x-dropdown-link>
                            </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open" 
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-200 hover:text-white hover:bg-primary-dark focus:outline-none focus:bg-primary-dark/50 transition duration-150 ease-in-out">
                        <span class="sr-only">Abrir menú principal</span>
                        <!-- Ícono de hamburguesa -->
                        <i class="fas fa-bars h-6 w-6" x-show="!open"></i>
                        <!-- Ícono de cerrar -->
                        <i class="fas fa-times h-6 w-6" x-show="open"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-primary">
        <div class="pt-2 pb-3 space-y-1">
            <!-- Dashboard -->
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="flex items-center">
                <i class="fas fa-chart-line w-5 mr-2"></i>
                <span>Dashboard</span>
            </x-responsive-nav-link>

            <!-- Clientes Section -->
            <div class="border-l-4 border-transparent">
                <div class="text-gray-300 px-4 py-2 text-xs font-semibold">CLIENTES</div>
                <x-responsive-nav-link :href="route('clients.index')" :active="request()->routeIs('clients.*')">
                    <i class="fas fa-users w-5 mr-2"></i> Todos los Clientes
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('establishments.indexAll')" :active="request()->routeIs('establishments.*')">
                    <i class="fas fa-building w-5 mr-2"></i> Sedes
                </x-responsive-nav-link>
            </div>

            <!-- Ventas Section -->
            <div class="border-l-4 border-transparent">
                <div class="text-gray-300 px-4 py-2 text-xs font-semibold">VENTAS</div>
                <x-responsive-nav-link :href="route('deals.index')" :active="request()->routeIs('deals.*')">
                    <i class="fas fa-project-diagram w-5 mr-2"></i> Pipeline
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('leads.index')" :active="request()->routeIs('leads.*')">
                    <i class="fas fa-funnel-dollar w-5 mr-2"></i> Leads
                </x-responsive-nav-link>
            </div>

            <!-- Sección oculta en versión 1.0 estable -->
            <div class="border-l-4 border-transparent">
                {{-- <div class="text-gray-300 px-4 py-2 text-xs font-semibold">AUTOMATIZACIÓN</div> --}} <!-- Oculto en versión 1.0 estable -->
                {{-- Enlaces de Secuencias e Inscripciones ocultos en versión 1.0 estable --}}
                {{-- <x-responsive-nav-link :href="route('sequences.index')" :active="request()->routeIs('sequences.*')">
                    <i class="fas fa-sitemap w-5 mr-2"></i> Secuencias
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('enrollments.index')" :active="request()->routeIs('enrollments.*')">
                    <i class="fas fa-users-cog w-5 mr-2"></i> Inscripciones
                </x-responsive-nav-link> --}}
            </div>

            <!-- Gestión Section -->
            <div class="border-l-4 border-transparent">
                <div class="text-gray-300 px-4 py-2 text-xs font-semibold">GESTIÓN</div>
                <x-responsive-nav-link :href="route('calendar.index')" :active="request()->routeIs('calendar.*')">
                    <i class="fas fa-calendar-alt w-5 mr-2"></i> Calendario
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('tasks.index')" :active="request()->routeIs('tasks.*')">
                    <i class="fas fa-clipboard-list w-5 mr-2"></i> Tareas
                </x-responsive-nav-link>
            </div>

            <!-- Reports -->
            <x-responsive-nav-link :href="route('reports.sales')" :active="request()->routeIs('reports.sales')">
                <i class="fas fa-chart-bar w-5 mr-2"></i> Reportes
            </x-responsive-nav-link>

            <!-- Admin Panel -->
            @if(auth()->user()->is_admin)
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                    <i class="fas fa-shield-alt w-5 mr-2"></i> Panel Admin
                </x-responsive-nav-link>
            @endif
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

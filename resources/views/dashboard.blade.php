<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold ...">
    Panel de Control
</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Sección de KPIs -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Tarjeta Leads Activos -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                    <h3 class="text-lg font-semibold text-gray-500 dark:text-gray-400">Leads Activos</h3>
                    <p class="text-3xl font-bold mt-2 text-gray-800 dark:text-gray-200">{{ $activeLeadsCount }}</p>
                </div>
                <!-- Tarjeta Deals Abiertos -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                    <h3 class="text-lg font-semibold text-gray-500 dark:text-gray-400">Deals Abiertos</h3>
                    <p class="text-3xl font-bold mt-2 text-gray-800 dark:text-gray-200">{{ $openDealsCount }}</p>
                </div>
                <!-- Tarjeta Valor del Pipeline -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                    <h3 class="text-lg font-semibold text-gray-500 dark:text-gray-400">Valor del Pipeline</h3>
                    <p class="text-3xl font-bold mt-2 text-gray-800 dark:text-gray-200">${{ number_format($pipelineValue, 0) }}</p>
                </div>
            </div>

            <!-- Sección de Tareas y Deals Recientes -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tarjeta Tareas Pendientes -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Próximas Tareas</h3>
                    <div class="space-y-3">
                        @forelse ($upcomingTasks as $task)
                            <div class="border-l-4 @if($task->status == 'pendiente') border-yellow-500 @else border-blue-500 @endif pl-3">
                                <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $task->title }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Vence: {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400">¡No tienes tareas pendientes!</p>
                        @endforelse
                    </div>
                </div>
                <!-- Tarjeta Deals Recientes -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Deals Abiertos Recientemente</h3>
                    <div class="space-y-3">
                        @forelse ($recentDeals as $deal)
                            <div>
                                <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $deal->name }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Cliente: {{ $deal->client->name }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400">No hay deals abiertos.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
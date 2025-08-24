<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Panel de Control
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Sección de KPIs Generales -->
           <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mb-6 justify-center mx-auto">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm text-center">
        <h3 class="text-lg font-semibold text-gray-500 dark:text-gray-400">Clientes</h3>
        <p class="text-3xl font-bold mt-2">{{ $clientCount }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm text-center">
        <h3 class="text-lg font-semibold text-gray-500 dark:text-gray-400">Contactos</h3>
        <p class="text-3xl font-bold mt-2">{{ $contactCount }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm text-center">
        <h3 class="text-lg font-semibold text-gray-500 dark:text-gray-400">Secuencias Activas</h3>
        <p class="text-3xl font-bold mt-2">{{ $activeSequencesCount }}</p>
    </div>
</div>

            <!-- Sección de Pipeline -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm mb-6">
                <h3 class="text-lg font-semibold mb-4">Pipeline de Ventas</h3>
                <div class="grid grid-cols-2 gap-6 text-center">
                    <div>
                        <h4 class="text-gray-500 dark:text-gray-400">Deals Abiertos</h4>
                        <p class="text-2xl font-bold">{{ $pipelineStats['openDealsCount'] }}</p>
                    </div>
                    <div>
                        <h4 class="text-gray-500 dark:text-gray-400">Valor Total</h4>
                        <p class="text-2xl font-bold">${{ number_format($pipelineStats['pipelineValue'], 0) }}</p>
                    </div>
                </div>
            </div>

            <!-- Sección de Leads -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm mb-6">
                <h3 class="text-lg font-semibold mb-4">Embudo de Leads</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                    <div>
                        <h4 class="text-gray-500 dark:text-gray-400">Nuevos</h4>
                        <p class="text-2xl font-bold">{{ $leadStatusCounts['nuevo'] ?? 0 }}</p>
                    </div>
                    <div>
                        <h4 class="text-gray-500 dark:text-gray-400">Contactados</h4>
                        <p class="text-2xl font-bold">{{ $leadStatusCounts['contactado'] ?? 0 }}</p>
                    </div>
                    <div>
                        <h4 class="text-gray-500 dark:text-gray-400">Calificados</h4>
                        <p class="text-2xl font-bold">{{ $leadStatusCounts['calificado'] ?? 0 }}</p>
                    </div>
                    <div>
                        <h4 class="text-gray-500 dark:text-gray-400">Perdidos</h4>
                        <p class="text-2xl font-bold">{{ $leadStatusCounts['perdido'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Sección de Tareas y Deals Recientes -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tarjeta Tareas Pendientes -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Próximas Tareas</h3>
                    <div class="space-y-3">
                        @forelse ($upcomingTasks as $task)
                            <div class="border-l-4 @if($task->status == 'pendiente') border-yellow-500 @else border-blue-500 @endif pl-3">
                                <p class="font-semibold">{{ $task->title }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Vence: {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400">¡No tienes tareas pendientes!</p>
                        @endforelse
                    </div>
                </div>
                <!-- Tarjeta Deals Recientes -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Deals Abiertos Recientemente</h3>
                    <div class="space-y-3">
                        @forelse ($recentDeals as $deal)
                            <div>
                                <p class="font-semibold">{{ $deal->name }}</p>
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
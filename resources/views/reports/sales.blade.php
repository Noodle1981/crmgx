<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Reporte de Ventas ({{ $selectedDate->translatedFormat('F Y') }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- FORMULARIO DE FILTROS -->
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm mb-6">
                <form action="{{ route('reports.sales') }}" method="GET" class="flex flex-wrap items-center space-x-4">
                    <div>
                        <label for="year" class="text-sm font-medium text-gray-700 dark:text-gray-300">Año:</label>
                        <select name="year" id="year" class="rounded-md dark:bg-gray-900 dark:text-gray-300 border-gray-300 dark:border-gray-700">
                            @foreach($availableYears as $availableYear)
                                <option value="{{ $availableYear }}" {{ $availableYear == $year ? 'selected' : '' }}>
                                    {{ $availableYear }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="month" class="text-sm font-medium text-gray-700 dark:text-gray-300">Mes:</label>
                        <select name="month" id="month" class="rounded-md dark:bg-gray-900 dark:text-gray-300 border-gray-300 dark:border-gray-700">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded-md">
                        Filtrar
                    </button>
                </form>
            </div>

            <!-- KPIs Principales -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-6 text-center">
                <div class="bg-green-500 text-white p-4 rounded-lg shadow-sm">
                    <h3 class="font-semibold">Total Ganado</h3>
                    <p class="text-2xl font-bold">${{ number_format($totalWonValue, 0) }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm">
                    <h3 class="font-semibold text-gray-500 dark:text-gray-400">Deals Ganados</h3>
                    <p class="text-2xl font-bold">{{ $wonCount }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm">
                    <h3 class="font-semibold text-gray-500 dark:text-gray-400">Deals Perdidos</h3>
                    <p class="text-2xl font-bold">{{ $lostCount }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm">
                    <h3 class="font-semibold text-gray-500 dark:text-gray-400">Ticket Promedio</h3>
                    <p class="text-2xl font-bold">${{ number_format($averageDealSize, 0) }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm">
                    <h3 class="font-semibold text-gray-500 dark:text-gray-400">Tasa de Conversión</h3>
                    <p class="text-2xl font-bold">{{ number_format($conversionRate, 1) }}%</p>
                </div>
            </div>

            <!-- Listas de Deals Cerrados -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Deals Ganados -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Deals Ganados este Mes</h3>
                        @forelse($wonDeals as $deal)
                            <div class="py-2 border-b dark:border-gray-700 last:border-b-0">
                                <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $deal->name }} - <span class="text-green-500">${{ number_format($deal->value, 2) }}</span></p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $deal->client->name }} | Cerrado: {{ $deal->updated_at->format('d/m/Y') }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400">Aún no has ganado ningún deal en este período.</p>
                        @endforelse
                    </div>
                </div>
                <!-- Deals Perdidos -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Deals Perdidos este Mes</h3>
                        @forelse($lostDeals as $deal)
                            <div class="py-2 border-b dark:border-gray-700 last:border-b-0">
                                <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $deal->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $deal->client->name }} | Cerrado: {{ $deal->updated_at->format('d/m/Y') }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400">No se han perdido deals en este período.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
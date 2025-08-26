<x-app-layout>
    <x-slot name="header">
        Reporte de Ventas ({{ $selectedDate->translatedFormat('F Y') }})
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        {{-- ========================================================== --}}
        {{-- AQUÍ ESTÁ EL FORMULARIO DE FILTROS QUE FALTABA --}}
        {{-- ========================================================== --}}
        <x-card class="mb-8">
            <form action="{{ route('reports.sales') }}" method="GET" class="flex flex-wrap items-center gap-4">
                <div class="flex items-center space-x-2">
                    <x-input-label for="year" value="Año:" class="mb-0" />
                    <select name="year" id="year" class="text-sm bg-gray-900/60 border-white/10 rounded-lg focus:border-aurora-cyan focus:ring-aurora-cyan/40">
                        @foreach($availableYears as $availableYear)
                            <option value="{{ $availableYear }}" @selected($availableYear == $year)>{{ $availableYear }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center space-x-2">
                    <x-input-label for="month" value="Mes:" class="mb-0" />
                    <select name="month" id="month" class="text-sm bg-gray-900/60 border-white/10 rounded-lg focus:border-aurora-cyan focus:ring-aurora-cyan/40">
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" @selected($m == $month)>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                        @endfor
                    </select>
                </div>
                <x-primary-button type="submit">Filtrar</x-primary-button>
            </form>
        </x-card>

        <!-- KPIs Principales -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-8">
            <div class="col-span-2 md:col-span-3 lg:col-span-1 bg-gradient-to-br from-green-400 to-aurora-cyan p-6 rounded-2xl shadow-xl flex flex-col justify-center text-center">
                <h3 class="font-headings text-lg text-dark-void/80">Total Ganado</h3>
                <p class="text-5xl font-bold text-dark-void mt-1">${{ number_format($totalWonValue, 0) }}</p>
            </div>
            
            <x-kpi-card title="Deals Ganados" value="{{ $wonCount }}" icon="fas fa-trophy" class="lg:col-span-1" />
            <x-kpi-card title="Deals Perdidos" value="{{ $lostCount }}" icon="fas fa-times-circle" class="lg:col-span-1" />
            <x-kpi-card title="Ticket Promedio" value="${{ number_format($averageDealSize, 0) }}" icon="fas fa-dollar-sign" class="lg:col-span-1" />
            <x-kpi-card title="Tasa de Conversión" value="{{ number_format($conversionRate, 1) }}%" icon="fas fa-chart-line" class="lg:col-span-1" />
        </div>

        <!-- Listas de Deals Cerrados -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-card>
                <x-slot name="header">
                    <div class="flex items-center space-x-3 text-green-400">
                        <i class="fas fa-check-circle text-2xl"></i>
                        <h3 class="font-headings text-xl text-light-text">Deals Ganados</h3>
                    </div>
                </x-slot>
                <div class="space-y-4 divide-y divide-white/10">
                    @forelse($wonDeals as $deal)
                        <div class="pt-4 first:pt-0">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold text-light-text">{{ $deal->name }}</p>
                                    <p class="text-sm text-light-text-muted">{{ $deal->client->name }} | {{ $deal->updated_at->format('d/m/Y') }}</p>
                                </div>
                                <span class="font-bold text-green-400">${{ number_format($deal->value, 0) }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-light-text-muted py-8">Aún no has ganado ningún deal en este período.</p>
                    @endempty
                </div>
            </x-card>

            <x-card>
                <x-slot name="header">
                    <div class="flex items-center space-x-3 text-aurora-red-pop">
                        <i class="fas fa-times-circle text-2xl"></i>
                        <h3 class="font-headings text-xl text-light-text">Deals Perdidos</h3>
                    </div>
                </x-slot>
                <div class="space-y-4 divide-y divide-white/10">
                    @forelse($lostDeals as $deal)
                        <div class="pt-4 first:pt-0">
                            <p class="font-semibold text-light-text">{{ $deal->name }}</p>
                            <p class="text-sm text-light-text-muted">{{ $deal->client->name }} | {{ $deal->updated_at->format('d/m/Y') }}</p>
                        </div>
                    @empty
                        <p class="text-center text-light-text-muted py-8">No se han perdido deals en este período.</p>
                    @endempty
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
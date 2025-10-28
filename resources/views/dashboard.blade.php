<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text-main leading-tight">
            Dashboard
        </h2>
    </x-slot>

    {{-- ========================================================== --}}
    {{-- SECCIÓN 1: TARJETAS DE KPI --}}
    {{-- ========================================================== --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <x-kpi-card title="Clientes" :value="$clientCount" icon="fas fa-users" />
        <x-kpi-card title="Contactos" :value="$contactCount" icon="fas fa-address-book" />
        <x-kpi-card title="Leads Activos" :value="$activeLeadsCount" icon="fas fa-filter" />
        <x-kpi-card title="Secuencias Activas" :value="$activeSequencesCount" icon="fas fa-stream" />
    </div>

    {{-- ========================================================== --}}
    {{-- SECCIÓN 2: GRÁFICO DEL PIPELINE --}}
    {{-- ========================================================== --}}
    <x-card class="mb-8">
        <x-slot name="header">
            <h3 class="font-headings text-xl">Pipeline de Ventas</h3>
        </x-slot>
        
        <div>
            <h3 class="text-lg font-semibold mb-4">Valor del Pipeline por Etapa</h3>
            <div id="pipelineChart"></div>
        </div>

        <x-slot name="footer">
            <div class="flex justify-between items-center text-sm">
                <span>Deals Abiertos: <strong>{{ $pipelineStats['openDealsCount'] }}</strong></span>
                <span>Valor Total: <strong class="text-white">${{ number_format($pipelineStats['pipelineValue'], 0) }}</strong></span>
            </div>
        </x-slot>
    </x-card>

    {{-- ========================================================== --}}
    {{-- SECCIÓN 3: TAREAS Y DEALS RECIENTES --}}
    {{-- ========================================================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-card>
            <x-slot name="header">
                <h3 class="font-headings text-xl">Próximas Tareas</h3>
            </x-slot>
            <div class="space-y-4">
                @forelse ($upcomingTasks as $task)
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 mt-1 h-3 w-3 rounded-full @if($task->status == 'pendiente') bg-white animate-pulse @else bg-primary-light @endif"></div>
                        <div>
                            <p class="font-semibold">{{ $task->title }}</p>
                            <x-smart-date :date="$task->due_date" with-color="true" with-icon="true" />
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-check-circle text-4xl mb-3"></i>
                        <p>¡No tienes tareas pendientes!</p>
                    </div>
                @endforelse
            </div>
        </x-card>

        <x-card>
            <x-slot name="header">
                <h3 class="font-headings text-xl">Deals Abiertos Recientemente</h3>
            </x-slot>
            <div class="space-y-4 divide-y divide-primary-light/50">
                @forelse ($recentDeals as $deal)
                    <div class="pt-4 first:pt-0">
                        <p class="font-semibold">{{ $deal->name }}</p>
                        <p class="text-sm text-primary-light">Cliente: {{ $deal->client->name }}</p>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-folder-open text-4xl mb-3"></i>
                        <p>No hay deals abiertos recientemente.</p>
                    </div>
                @endforelse
            </div>
        </x-card>
    </div>
</x-app-layout>

{{-- ========================================================== --}}
{{-- SECCIÓN 4: SCRIPT PARA EL GRÁFICO --}}
{{-- ========================================================== --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const options = {
            series: [{
                name: 'Valor',
                data: []
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: { show: false },
                foreColor: '#FFFFFF' // Texto blanco para los ejes
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 4,
                },
            },
            colors: ['#FFAC4E'], // Naranja claro para las barras
            dataLabels: { enabled: false },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: [],
                labels: {
                    style: {
                        colors: '#FFFFFF',
                    },
                },
                axisBorder: {
                    color: '#FFAC4E' 
                },
                axisTicks: {
                    color: '#FFAC4E'
                }
            },
            yaxis: {
                title: { 
                    text: '$ (Valor)',
                    style: {
                        color: '#FFFFFF'
                    }
                },
                labels: {
                    style: {
                        colors: '#FFFFFF',
                    },
                    formatter: function (val) {
                        if (val >= 1000) {
                            return ' + (val / 1000).toFixed(0) + 'k';
                        }
                        return ' + val;
                    }
                }
            },
            grid: {
                borderColor: '#FFFFFF30' // Lineas de la grilla blancas con transparencia
            },
            fill: { opacity: 1 },
            tooltip: {
                theme: 'dark',
                y: {
                    formatter: function (val) {
                        return "$ " + val.toLocaleString('es-ES');
                    }
                }
            }
        };

        const chart = new ApexCharts(document.querySelector("#pipelineChart"), options);
        chart.render();

        fetch("{{ route('charts.pipeline') }}")
            .then(response => response.json())
            .then(data => {
                chart.updateOptions({
                    xaxis: {
                        categories: data.labels
                    },
                    series: [{
                        data: data.series
                    }],
                });
            })
            .catch(error => {
                console.error('Hubo un problema con la operación fetch:', error);
                document.querySelector("#pipelineChart").innerHTML = '<p class="text-center text-white">No se pudieron cargar los datos del gráfico.</p>';
            });
    });
</script>
@endpush

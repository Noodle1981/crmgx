<x-app-layout>
    <x-slot name="header">
        <h2 class="font-headings text-3xl font-extrabold text-primary-light tracking-tight drop-shadow mb-2">
            Dashboard
        </h2>
    </x-slot>

    {{-- ========================================================== --}}
    {{-- SECCIÓN 1: TARJETAS DE KPI --}}
    {{-- ========================================================== --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <x-kpi-card title="Clientes" :value="$clientCount" icon="fas fa-users" class="bg-primary-dark text-white shadow-xl" />
    <x-kpi-card title="Contactos" :value="$contactCount" icon="fas fa-address-book" class="bg-primary-dark text-white shadow-xl" />
    <x-kpi-card title="Leads Activos" :value="$activeLeadsCount" icon="fas fa-filter" class="bg-primary-dark text-white shadow-xl" />
    {{-- <x-kpi-card title="Secuencias Activas" :value="$activeSequencesCount" icon="fas fa-stream" class="bg-primary-dark text-white shadow-xl" /> --}}  <!-- Oculto en versión 1.0 estable -->
    </div>


    {{-- ========================================================== --}}
    {{-- SECCIÓN 2: GRÁFICO DEL PIPELINE --}}
    {{-- ========================================================== --}}

    <x-card class="mb-8 bg-white border border-primary-light shadow-lg">
        <x-slot name="header">
            <h3 class="font-headings text-3xl text-primary-dark tracking-tight mb-1">Pipeline de Ventas</h3>
        </x-slot>
        <div id="pipelineChart"></div>
        <x-slot name="footer">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 py-4">
                <div class="flex flex-col items-center md:items-start bg-orange-xamanen rounded-xl px-6 py-4 shadow">
                    <span class="uppercase text-lg font-extrabold tracking-wide mb-1 text-white">DEALS ABIERTOS</span>
                    <span class="text-5xl font-extrabold text-white">{{ $pipelineStats['openDealsCount'] }}</span>
                </div>
                <div class="flex flex-col items-center md:items-end bg-orange-xamanen rounded-xl px-6 py-4 shadow">
                    <span class="uppercase text-lg font-extrabold tracking-wide mb-1 text-white">VALOR TOTAL</span>
                    <span class="text-5xl font-extrabold text-white">${{ number_format($pipelineStats['pipelineValue'], 0) }}</span>
                </div>
            </div>
        </x-slot>
    </x-card>

    {{-- ========================================================== --}}
    {{-- SECCIÓN 3: TAREAS Y DEALS RECIENTES --}}
    {{-- ========================================================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-card class="bg-white border border-primary-light shadow-lg">
            <x-slot name="header">
                <div class="flex items-center gap-3">
                    <i class="fas fa-calendar-check text-3xl text-primary drop-shadow"></i>
                    <h3 class="font-headings text-2xl text-primary-dark tracking-tight">Próximas Tareas</h3>
                </div>
            </x-slot>
            <div class="space-y-4">
                @forelse ($upcomingTasks as $task)
                    <div class="flex items-center gap-4 p-4 rounded-xl bg-surface shadow hover:bg-primary-light/10 transition-all duration-200 border border-primary-light">
                        <div class="flex-shrink-0">
                            <i class="fas fa-tasks text-2xl text-primary"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-lg text-black mb-1">{{ $task->title }}</p>
                            <x-smart-date :date="$task->due_date" with-color="true" with-icon="true" class="text-2xl font-extrabold text-black" />
                        </div>
                        <div>
                            @if($task->status == 'pendiente')
                                <span class="px-3 py-1 rounded-full bg-orange-xamanen text-white text-xs font-bold animate-pulse">Pendiente</span>
                            @elseif($task->status == 'en_proceso')
                                <span class="px-3 py-1 rounded-full bg-blue-500 text-white text-xs font-bold">En proceso</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-green-500 text-white text-xs font-bold">Completado</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-check-circle text-5xl text-primary-dark mb-3 animate-bounce"></i>
                        <p class="text-lg text-primary-dark font-bold">¡No tienes tareas pendientes!</p>
                    </div>
                @endforelse
            </div>
        </x-card>

        <x-card class="bg-white border border-primary-light shadow-lg">
            <x-slot name="header">
                <div class="flex items-center gap-3">
                    <i class="fas fa-briefcase text-3xl text-primary drop-shadow"></i>
                    <h3 class="font-headings text-2xl text-primary-dark tracking-tight">Deals Abiertos Recientemente</h3>
                </div>
            </x-slot>
            <div class="space-y-4">
                @forelse ($recentDeals as $deal)
                    <div class="flex items-center gap-4 p-4 rounded-xl bg-surface shadow hover:bg-primary-light/10 transition-all duration-200 border border-primary-light">
                        <div class="flex-shrink-0">
                            <i class="fas fa-file-contract text-2xl text-primary"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-xl text-black mb-1">{{ $deal->name }}</p>
                            <p class="text-lg text-black font-bold">Cliente: {{ $deal->client->name }}</p>
                        </div>
                        <div>
                            <span class="px-3 py-1 rounded-full bg-blue-500 text-white text-xs font-bold">Abierto</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-folder-open text-5xl text-primary-dark mb-3 animate-bounce"></i>
                        <p class="text-lg text-primary-dark font-bold">No hay deals abiertos recientemente.</p>
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

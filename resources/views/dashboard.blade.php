<x-app-layout>
    <x-slot name="header">
       Dashboard
    </x-slot>

    {{-- ========================================================== --}}
    {{-- SECCIÓN 1: TARJETAS DE KPI --}}
    {{-- ========================================================== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <x-kpi-card title="Clientes" value="{{ $clientCount }}" icon="fas fa-users" />
        <x-kpi-card title="Contactos" value="{{ $contactCount }}" icon="fas fa-address-book" />
        <x-kpi-card title="Secuencias Activas" value="{{ $activeSequencesCount }}" icon="fas fa-cogs" />
    </div>

    {{-- ========================================================== --}}
    {{-- SECCIÓN 2: GRÁFICO DEL PIPELINE --}}
    {{-- ========================================================== --}}
    <x-card class="mb-8">
        <x-slot name="header">
            <h3 class="font-headings text-xl">Pipeline de Ventas</h3>
        </x-slot>
        
        {{-- Contenido del gráfico dentro de la tarjeta de cristal --}}
        <div>
            <h3 class="text-lg font-semibold text-light-text mb-4">Valor del Pipeline por Etapa</h3>
            <div id="pipelineChart"></div>
        </div>

        <x-slot name="footer">
            <div class="flex justify-between items-center text-sm">
                <span>Deals Abiertos: <strong class="text-light-text">{{ $pipelineStats['openDealsCount'] }}</strong></span>
                <span>Valor Total: <strong class="text-aurora-cyan">${{ number_format($pipelineStats['pipelineValue'], 0) }}</strong></span>
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
                        <div class="flex-shrink-0 mt-1 h-3 w-3 rounded-full @if($task->status == 'pendiente') bg-aurora-red-pop animate-pulse @else bg-aurora-blue @endif"></div>
                        <div>
                            <p class="font-semibold text-light-text">{{ $task->title }}</p>
                            <x-smart-date :date="$task->due_date" with-color="true" with-icon="true" />
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-light-text-muted">
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
            <div class="space-y-4 divide-y divide-white/10">
                @forelse ($recentDeals as $deal)
                    <div class="pt-4 first:pt-0">
                        <p class="font-semibold text-light-text">{{ $deal->name }}</p>
                        <p class="text-sm text-light-text-muted">Cliente: {{ $deal->client->name }}</p>
                    </div>
                @empty
                    <div class="text-center py-8 text-light-text-muted">
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
        // Opciones del gráfico
        const options = {
            series: [{
                name: 'Valor',
                data: []
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: { show: false },
                foreColor: '#9ca3af' // Color del texto de los ejes (gris para modo oscuro)
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 4, // Bordes redondeados para las barras
                },
            },
            colors: ['#30EEE2'], // Usamos nuestro color --aurora-cyan
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
                        colors: '#9ca3af',
                    },
                },
                axisBorder: {
                    color: '#4A5568' // Color del eje X
                },
                axisTicks: {
                    color: '#4A5568' // Color de las marcas del eje X
                }
            },
            yaxis: {
                title: { 
                    text: '$ (Valor)',
                    style: {
                        color: '#9ca3af'
                    }
                },
                labels: {
                    style: {
                        colors: '#9ca3af',
                    },
                    formatter: function (val) {
                        if (val >= 1000) {
                            return '$' + (val / 1000).toFixed(0) + 'k';
                        }
                        return '$' + val;
                    }
                }
            },
            grid: {
                borderColor: '#2d3748' // Color de las líneas de la cuadrícula
            },
            fill: { opacity: 1 },
            tooltip: {
                theme: 'dark', // Usamos el tema oscuro para el tooltip
                y: {
                    formatter: function (val) {
                        return "$ " + val.toLocaleString('es-ES');
                    }
                }
            }
        };

        // Creamos la instancia del gráfico
        const chart = new ApexCharts(document.querySelector("#pipelineChart"), options);
        chart.render();

        // Hacemos la petición a nuestra API para obtener los datos reales
        fetch("{{ route('charts.pipeline') }}")
            .then(response => {
                if (!response.ok) {
                    throw new Error('La respuesta de la red no fue correcta');
                }
                return response.json();
            })
            .then(data => {
                // Actualizamos el gráfico con los datos del servidor
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
                document.querySelector("#pipelineChart").innerHTML = '<p class="text-center text-aurora-red-pop">No se pudieron cargar los datos del gráfico.</p>';
            });
    });
</script>
@endpush

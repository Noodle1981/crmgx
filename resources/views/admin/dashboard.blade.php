@extends('admin.layout')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Panel de Administración</h2>
    </div>

    <!-- Estadísticas generales (desde el controlador admin) -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="text-gray-900 text-xl mb-2">Usuarios Activos</div>
            <div class="text-3xl font-bold">{{ $stats['activeUsers'] ?? 0 }}</div>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="text-gray-900 text-xl mb-2">Clientes</div>
            <div class="text-3xl font-bold">{{ $stats['totalClients'] ?? 0 }}</div>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="text-gray-900 text-xl mb-2">Negocios Activos</div>
            <div class="text-3xl font-bold">{{ $stats['activeDeals'] ?? 0 }}</div>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="text-gray-900 text-xl mb-2">Valor Pipeline</div>
            <div class="text-3xl font-bold">${{ number_format($stats['pipelineValue'] ?? 0, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Top Vendedores del Mes</h3>
                <div class="space-y-4">
                    @foreach($topSellers as $seller)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-user text-gray-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $seller->name }}</p>
                                <p class="text-xs text-gray-500">{{ $seller->deals_count ?? 0 }} negocios</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900">${{ number_format($seller->deals_value ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Estado de Leads</h3>
                <div class="space-y-4">
                    @foreach($leadConversion as $status)
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $status['name'] }}</span>
                            <span class="text-sm font-medium text-gray-700">{{ $status['count'] }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $status['percentage'] }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Actividad Reciente</h3>
                <a href="{{ route('admin.logs') }}" class="text-blue-600 hover:text-blue-800 text-sm">Ver todos los logs <i class="fas fa-arrow-right ml-1"></i></a>
            </div>
            <div class="space-y-4">
                @foreach($recentActivity as $activity)
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <span class="w-8 h-8 rounded-full {{ $activity->type_color ?? 'bg-gray-500' }} flex items-center justify-center">
                            <i class="fas {{ $activity->type_icon ?? 'fa-dot-circle' }} text-white"></i>
                        </span>
                    </div>
                    <div class="ml-3 flex-grow">
                        <p class="text-sm text-gray-900">
                            <span class="font-semibold">{{ $activity->user->name ?? 'Sistema' }}</span>
                            {{ $activity->description ?? '' }}
                        </p>
                        <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() ?? '' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
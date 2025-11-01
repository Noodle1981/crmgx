<x-app-layout>
    <x-slot name="header">
        Reporte Global de Ventas ({{ $selectedDate->translatedFormat('F Y') }})
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Ganado</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comisión</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deals Ganados</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deals Perdidos</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tasa Conversión</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket Promedio</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($reportData as $data)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $data['userName'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-bold">${{ number_format($data['totalWonValue'], 0) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-500 font-bold">${{ number_format($data['commissionAmount'], 0) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $data['wonCount'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $data['lostCount'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($data['conversionRate'], 1) }}%</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($data['averageDealSize'], 0) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            No hay datos de ventas para mostrar en este período.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

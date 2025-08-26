<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <span>{{ __('Leads') }}</span>
            <a href="{{ route('leads.create') }}">
                <x-primary-button>
                    <i class="fas fa-user-plus mr-2"></i>
                    Crear Nuevo Lead
                </x-primary-button>
            </a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4">
            <x-session-status :status="session('success')" />
        </div>
    @endif

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <x-card class="!p-0">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b border-white/10">
                        <tr>
                            <th class="p-4 text-left font-semibold text-light-text-muted uppercase tracking-wider">Nombre</th>
                            <th class="p-4 text-left font-semibold text-light-text-muted uppercase tracking-wider">Compañía</th>
                            {{-- NUEVA COLUMNA --}}
                            <th class="p-4 text-left font-semibold text-light-text-muted uppercase tracking-wider">Creado</th>
                            <th class="p-4 text-left font-semibold text-light-text-muted uppercase tracking-wider">Estado</th>
                            <th class="relative p-4"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse ($leads as $lead)
                            <tr class="hover:bg-gray-800/50 transition-colors duration-200">
                                <td class="p-4 font-medium">
                                    <a href="{{ route('leads.edit', $lead) }}" class="text-aurora-cyan hover:underline">
                                        {{ $lead->name }}
                                    </a>
                                </td>
                                <td class="p-4 text-light-text-muted">{{ $lead->company }}</td>
                                {{-- NUEVA CELDA CON LA FECHA INTELIGENTE --}}
                                <td class="p-4">
                                    <x-smart-date :date="$lead->created_at" human="true" />
                                </td>
                                <td class="p-4">
                                    <span @class([
                                        'px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full',
                                        'bg-blue-500/10 text-blue-300' => $lead->status == 'nuevo',
                                        'bg-yellow-500/10 text-yellow-300' => $lead->status == 'contactado',
                                        'bg-green-500/10 text-green-300' => $lead->status == 'calificado',
                                        'bg-red-500/10 text-red-300' => $lead->status == 'perdido',
                                    ])>
                                        {{ ucfirst($lead->status) }}
                                    </span>
                                </td>
                                <td class="p-4 text-right text-sm font-medium space-x-4 flex items-center justify-end">
                                    @if ($lead->status === 'calificado')
                                        <form action="{{ route('leads.convert', $lead) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="font-bold text-green-400 hover:text-green-300 transition duration-150 flex items-center space-x-1">
                                                <i class="fas fa-check-circle"></i>
                                                <span>Convertir</span>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('leads.edit', $lead) }}" class="text-light-text-muted hover:text-aurora-cyan transition" title="Editar"><i class="fas fa-pen"></i></a>
                                    <form action="{{ route('leads.destroy', $lead) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-light-text-muted hover:text-aurora-red-pop transition" title="Eliminar"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                {{-- NO OLVIDES CAMBIAR EL COLSPAN A 5 --}}
                                <td colspan="5" class="text-center text-light-text-muted py-16">
                                    <i class="fas fa-search text-4xl mb-3"></i>
                                    <p>No se encontraron leads activos.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($leads->hasPages())
                <div class="p-4 border-t border-white/10">{{ $leads->links() }}</div>
            @endif
        </x-card>
    </div>
</x-app-layout>
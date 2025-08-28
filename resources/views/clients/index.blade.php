<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <span>{{ __('Clientes') }}</span>
            <a href="{{ route('clients.create') }}">
                <x-primary-button>
                    <i class="fas fa-user-plus mr-2"></i>
                    Crear Nuevo Cliente
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
                            <th class="p-4 text-left font-semibold text-light-text-muted uppercase tracking-wider">Email</th>
                            {{-- ========================================================== --}}
                            {{-- 1. NUEVA COLUMNA DE TELÉFONO --}}
                            {{-- ========================================================== --}}
                            <th class="p-4 text-left font-semibold text-light-text-muted uppercase tracking-wider">Teléfono</th>
                            <th class="relative p-4"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse ($clients as $client)
                            <tr class="hover:bg-gray-800/50 transition-colors duration-200">
                                <td class="p-4 font-medium text-light-text">
                                    {{-- El nombre ya no es un enlace, es solo texto --}}
                                    {{ $client->name }}
                                </td>
                                <td class="p-4 text-light-text-muted">{{ $client->company }}</td>
                                <td class="p-4 text-light-text-muted">{{ $client->email }}</td>
                                {{-- CELDA PARA EL TELÉFONO --}}
                                <td class="p-4 text-light-text-muted">{{ $client->phone ?? 'N/A' }}</td>
                                <td class="p-4 text-right text-sm font-medium">
                                    {{-- ========================================================== --}}
                                    {{-- 2. ACCIONES CON LUPA AÑADIDA --}}
                                    {{-- ========================================================== --}}
                                    <div class="flex items-center justify-end space-x-4">
                                        {{-- El nuevo icono de Lupa para ver detalles --}}
                                        <a href="{{ route('clients.show', $client) }}" class="text-light-text-muted hover:text-aurora-cyan transition" title="Ver Detalles">
                                            <i class="fas fa-search"></i>
                                        </a>
                                        <a href="{{ route('clients.edit', $client) }}" class="text-light-text-muted hover:text-aurora-cyan transition" title="Editar Cliente">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <form action="{{ route('clients.destroy', $client) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro? Se eliminarán también todos sus contactos y deals.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-light-text-muted hover:text-aurora-red-pop transition" title="Eliminar Cliente">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                {{-- No olvides cambiar el colspan a 5 --}}
                                <td colspan="5" class="text-center text-light-text-muted py-16">
                                    <i class="fas fa-users-slash text-4xl mb-3"></i>
                                    <p>No se encontraron clientes.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($clients->hasPages())
                <div class="p-4 border-t border-white/10">
                    {{ $clients->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <span>{{ __('Clientes') }}</span>
            
            {{-- 3. Botón "Crear Nuevo Cliente" adaptado --}}
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
        {{-- 1. Tarjeta principal con el fondo de cristal --}}
        <x-card class="!p-0">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b border-white/10">
                        <tr>
                            <th class="p-4 text-left font-semibold text-light-text-muted uppercase tracking-wider">Nombre</th>
                            <th class="p-4 text-left font-semibold text-light-text-muted uppercase tracking-wider">Compañía</th>
                            <th class="p-4 text-left font-semibold text-light-text-muted uppercase tracking-wider">Email</th>
                            <th class="relative p-4"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse ($clients as $client)
                            <tr class="hover:bg-gray-800/50 transition-colors duration-200">
                                <td class="p-4 font-medium">
                                    <a href="{{ route('clients.show', $client) }}" class="text-aurora-cyan hover:underline">
                                        {{ $client->name }}
                                    </a>
                                </td>
                                <td class="p-4 text-light-text-muted">{{ $client->company }}</td>
                                <td class="p-4 text-light-text-muted">{{ $client->email }}</td>
                                <td class="p-4 text-right text-sm font-medium">
                                    {{-- 2. Acciones con iconos representativos --}}
                                    <div class="flex items-center justify-end space-x-4">
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
                                <td colspan="4" class="text-center text-light-text-muted py-16">
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
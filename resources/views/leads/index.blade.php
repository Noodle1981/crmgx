<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Leads') }}
            </h2>
            <a href="{{ route('leads.create') }}">
                <x-primary-button>
                    <i class="fas fa-user-plus mr-2"></i>
                    Crear Nuevo Lead
                </x-primary-button>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4">
                    <x-session-status :status="session('success')" />
                </div>
            @endif

            <x-card class="!p-0">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="border-b border-white/10">
                            <tr>
                                <th class="p-4 text-left font-semibold text-light-text-muted uppercase">Nombre</th>
                                <th class="p-4 text-left font-semibold text-light-text-muted uppercase">Compañía</th>
                                <th class="p-4 text-left font-semibold text-light-text-muted uppercase">Creado</th>
                                <th class="p-4 text-left font-semibold text-light-text-muted uppercase">Estado</th>
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
                                    <td class="p-4 text-light-text-muted">{{ $lead->created_at->diffForHumans() }}</td>
                                    <td class="p-4">
                                        <span @class([
                                            'px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full',
                                            'bg-blue-500 text-back-900' => $lead->status == 'nuevo',
                                            'bg-yellow-500 text-back-900' => $lead->status == 'contactado',
                                            'bg-green-500 text-back-900' => $lead->status == 'calificado',
                                            'bg-red-500 text-back-900' => $lead->status == 'perdido',
                                        ])>
                                            {{ ucfirst($lead->status) }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-right text-sm font-medium space-x-4 flex items-center justify-end">
                                        <!-- Menú de Acciones de Estado -->
                                        <x-dropdown align="right" width="48">
                                            <x-slot name="trigger">
                                                <button class="text-light-text-muted hover:text-light-text transition" title="Cambiar Estado"><i class="fas fa-tasks"></i></button>
                                            </x-slot>
                                            <x-slot name="content">
                                                @if ($lead->status === 'nuevo')
                                                    <form action="{{ route('leads.updateStatus', $lead) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="contactado">
                                                        <button type="submit" class="block w-full px-4 py-2 text-start text-sm leading-5 text-light-text hover:bg-gray-800/50">Marcar Contactado</button>
                                                    </form>
                                                @endif
                                                @if ($lead->status === 'contactado')
                                                    <form action="{{ route('leads.updateStatus', $lead) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="calificado">
                                                        <button type="submit" class="block w-full px-4 py-2 text-start text-sm leading-5 text-light-text hover:bg-gray-800/50">Marcar Calificado</button>
                                                    </form>
                                                @endif
                                                @if ($lead->status === 'contactado' || $lead->status === 'calificado')
                                                     <form action="{{ route('leads.updateStatus', $lead) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="perdido">
                                                        <button type="submit" class="block w-full px-4 py-2 text-start text-sm leading-5 text-light-text hover:bg-gray-800/50">Marcar Perdido</button>
                                                    </form>
                                                @endif
                                            </x-slot>
                                        </x-dropdown>
                                        
                                        <!-- Botón de Convertir -->
                                        @if ($lead->status === 'calificado')
                                            <form action="{{ route('leads.convert', $lead) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="font-bold text-green-400 hover:text-green-300 transition" title="Convertir Lead"><i class="fas fa-check-circle"></i></button>
                                            </form>
                                        @endif
                                        
                                        <!-- Botones de Editar y Eliminar -->
                                        <a href="{{ route('leads.edit', $lead) }}" class="text-light-text-muted hover:text-aurora-cyan transition" title="Editar"><i class="fas fa-pen"></i></a>
                                        <form action="{{ route('leads.destroy', $lead) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-light-text-muted hover:text-aurora-red-pop transition" title="Eliminar"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
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
    </div>
</x-app-layout>
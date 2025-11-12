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

            <x-card class="!p-0 bg-white border border-primary-light shadow-lg">
                <div class="overflow-x-auto">
                    <table class="w-full text-base">
                        <thead class="border-b border-primary-light bg-surface">
                            <tr>
                                <th class="p-4 text-left font-bold text-primary-dark uppercase">Nombre</th>
                                <th class="p-4 text-left font-bold text-primary-dark uppercase">Compañía</th>
                                <th class="p-4 text-left font-bold text-primary-dark uppercase">Creado</th>
                                <th class="p-4 text-left font-bold text-primary-dark uppercase">Estado</th>
                                <th class="relative p-4"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-primary-light">
                            @forelse ($leads as $lead)
                                <tr class="hover:bg-primary-light/20 transition-colors duration-200">
                                    <td class="p-4 font-bold text-black">
                                        <a href="{{ route('leads.edit', $lead) }}" class="text-primary-dark hover:underline">
                                            {{ $lead->name }}
                                        </a>
                                    </td>
                                    <td class="p-4 text-black">{{ $lead->company }}</td>
                                    <td class="p-4 text-black">{{ $lead->created_at->diffForHumans() }}</td>
                                    <td class="p-4">
                                        <span @class([
                                            'px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full',
                                            'bg-blue-500 text-white' => $lead->status == 'nuevo',
                                            'bg-yellow-400 text-black' => $lead->status == 'contactado',
                                            'bg-green-500 text-white' => $lead->status == 'calificado',
                                            'bg-red-500 text-white' => $lead->status == 'perdido',
                                        ])>
                                            {{ ucfirst($lead->status) }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-right text-base font-medium space-x-4 flex items-center justify-end">
                                        <!-- Menú de Acciones de Estado -->
                                        <x-dropdown align="right" width="48">
                                            <x-slot name="trigger">
                                                <button class="text-primary-dark hover:text-primary transition" title="Cambiar Estado"><i class="fas fa-tasks"></i></button>
                                            </x-slot>
                                            <x-slot name="content">
                                                @if ($lead->status === 'nuevo')
                                                    <form action="{{ route('leads.updateStatus', $lead) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="contactado">
                                                        <button type="submit" class="block w-full px-4 py-2 text-start text-base leading-5 text-black hover:bg-primary-light/20">Marcar Contactado</button>
                                                    </form>
                                                @endif
                                                @if ($lead->status === 'contactado')
                                                    <form action="{{ route('leads.updateStatus', $lead) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="calificado">
                                                        <button type="submit" class="block w-full px-4 py-2 text-start text-base leading-5 text-black hover:bg-primary-light/20">Marcar Calificado</button>
                                                    </form>
                                                @endif
                                                @if ($lead->status === 'contactado' || $lead->status === 'calificado')
                                                     <form action="{{ route('leads.updateStatus', $lead) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="perdido">
                                                        <button type="submit" class="block w-full px-4 py-2 text-start text-base leading-5 text-black hover:bg-primary-light/20">Marcar Perdido</button>
                                                    </form>
                                                @endif
                                            </x-slot>
                                        </x-dropdown>
                                        
                                        <!-- Botón de Convertir -->
                                        @if ($lead->status === 'calificado')
                                            <form action="{{ route('leads.convert', $lead) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="font-bold text-green-600 hover:text-green-400 transition" title="Convertir Lead"><i class="fas fa-check-circle"></i></button>
                                            </form>
                                        @endif
                                        
                                        <!-- Botones de Editar y Eliminar -->
                                        <a href="{{ route('leads.edit', $lead) }}" class="text-primary-dark hover:text-primary transition" title="Editar"><i class="fas fa-pen"></i></a>
                                        <form action="{{ route('leads.destroy', $lead) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-primary-dark hover:text-orange-xamanen transition" title="Eliminar"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-primary-dark py-16">
                                        <i class="fas fa-search text-4xl mb-3"></i>
                                        <p>No se encontraron leads activos.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($leads->hasPages())
                    <div class="p-4 border-t border-primary-light">
                        @php
                            // Renderizar la paginación y aplicar clases personalizadas
                            $pagination = $leads->links()->toHtml();
                            // Reemplazar clases para los enlaces activos y normales
                            $pagination = str_replace('class="page-link"', 'class="page-link text-orange-xamanen font-bold hover:text-primary-dark transition"', $pagination);
                            $pagination = str_replace('class="active"', 'class="active bg-orange-xamanen text-white rounded font-bold"', $pagination);
                            echo $pagination;
                        @endphp
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>
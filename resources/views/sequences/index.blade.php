<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-headings font-bold text-2xl text-light-text">Plantillas de Secuencia</h2>
            <a href="{{ route('sequences.create') }}">
                <x-primary-button>
                    <i class="fas fa-plus mr-2"></i>
                    Crear Nueva Secuencia
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

            {{-- ========================================================== --}}
            {{-- INICIO DEL NUEVO PANEL EXPLICATIVO --}}
            {{-- ========================================================== --}}
            <div class="mb-8 bg-gray-900/60 backdrop-blur-xl border border-white/10 rounded-2xl p-6 flex items-start space-x-6">
                <div class="flex-shrink-0 text-4xl text-aurora-cyan mt-1">
                    <i class="fas fa-robot"></i>
                </div>
                <div>
                    <h3 class="font-headings text-2xl text-light-text mb-2">¿Qué son las Plantillas de Secuencia?</h3>
                    <p class="text-light-text-muted mb-4">
                        Una secuencia es una serie de pasos automáticos (como enviar un email o crear una tarea) que puedes diseñar una vez y luego aplicar a cualquier contacto. Es tu "robot" personal de seguimiento.
                    </p>
                    <h4 class="font-semibold text-light-text mb-2">¿Cómo se usan?</h4>
                    <ul class="space-y-2 text-light-text-muted">
                        <li class="flex items-center"><i class="fas fa-check-circle text-aurora-cyan mr-2"></i><strong>1. Crea una plantilla:</strong> Define un nombre para tu secuencia (ej. "Seguimiento Post-Reunión").</li>
                        <li class="flex items-center"><i class="fas fa-check-circle text-aurora-cyan mr-2"></i><strong>2. Añade los pasos:</strong> Construye el flujo, indicando qué hacer y cuántos días esperar entre cada acción.</li>
                        <li class="flex items-center"><i class="fas fa-check-circle text-aurora-cyan mr-2"></i><strong>3. Inscribe contactos:</strong> Desde la ficha de un cliente, puedes "inscribir" a un contacto en la secuencia que desees.</li>
                    </ul>
                </div>
            </div>
            {{-- ========================================================== --}}
            {{-- FIN DEL NUEVO PANEL EXPLICATIVO --}}
            {{-- ========================================================== --}}

            <x-card>
                <x-slot name="header">
                    <h3 class="font-headings text-xl">Mis Secuencias</h3>
                </x-slot>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="border-b border-white/10">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-light-text-muted uppercase">Nombre</th>
                                <th class="relative px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @forelse ($sequences as $sequence)
                                <tr>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('sequences.show', $sequence) }}" class="text-aurora-cyan hover:underline font-semibold">{{ $sequence->name }}</a>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium space-x-4">
                                        <a href="{{ route('sequences.edit', $sequence) }}" class="text-light-text-muted hover:text-aurora-cyan transition" title="Editar"><i class="fas fa-pencil-alt"></i></a>
                                        <form action="{{ route('sequences.destroy', $sequence) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-light-text-muted hover:text-aurora-red-pop transition" title="Eliminar"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-center py-8 text-light-text-muted">No has creado ninguna secuencia todavía.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($sequences->hasPages())
                    <div class="p-4 border-t border-white/10">
                        {{ $sequences->links() }}
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>
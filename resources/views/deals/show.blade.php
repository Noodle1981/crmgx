<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ $deal->name }}
            </h2>
                        <div class="flex items-center space-x-6">
                <a href="{{ route('deals.edit', $deal) }}" class="text-gray-400 hover:text-white transition" title="Editar">
                    <i class="fas fa-edit text-xl"></i>
                </a>
                <form action="{{ route('deals.destroy', $deal) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este deal?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-gray-400 hover:text-aurora-red transition" title="Eliminar">
                        <i class="fas fa-trash-alt text-xl"></i>
                    </button>
                </form>
                <a href="{{ route('deals.index') }}" class="text-gray-400 hover:text-white transition" title="Cerrar">
                    <i class="fas fa-times text-2xl"></i>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-900/60 backdrop-blur-xl border border-white/10 rounded-2xl overflow-hidden shadow-lg">
                <div class="p-6 text-white">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <h3 class="text-sm font-semibold text-light-text-muted">Cliente</h3>
                            <p class="text-lg text-light-text">{{ $deal->client->name }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-light-text-muted">Valor</h3>
                            <p class="text-lg text-aurora-cyan font-bold">${{ number_format($deal->value, 2) }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-light-text-muted">Etapa</h3>
                            <p class="text-lg text-light-text">{{ $deal->dealStage->name }}</p>
                        </div>
                    </div>
                    
                    <h3 class="text-lg font-bold text-aurora-cyan mt-8 mb-4">Historial de Actividades</h3>

                    <div class="space-y-6">
                        @forelse ($deal->activities->sortByDesc('created_at') as $activity)
                            <div class="flex space-x-4 items-start">
                                <div class="flex-shrink-0">
                                    <span class="h-10 w-10 rounded-full bg-gray-700 flex items-center justify-center text-aurora-cyan">
                                        <i class="fas fa-{{ match($activity->type) {'call' => 'phone', 'meeting' => 'users', 'email' => 'envelope', default => 'sticky-note'} }}"></i>
                                    </span>
                                </div>
                                <div class="flex-grow">
                                    <div class="flex justify-between items-center">
                                        <p class="font-semibold text-light-text">{{ $activity->user->name }}</p>
                                        <p class="text-xs text-light-text-muted">{{ $activity->created_at->diffForHumans() }}</p>
                                    </div>
                                    <p class="text-light-text-muted">{{ ucfirst($activity->type) }}</p>
                                    <p class="mt-2 text-light-text">{{ $activity->description }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <p class="text-light-text-muted">No hay actividades registradas para este deal.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

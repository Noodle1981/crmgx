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

                    @php
                        $createdAt = \Carbon\Carbon::parse($deal->created_at)->startOfDay();
                        $closesAt = $deal->expected_close_date ? \Carbon\Carbon::parse($deal->expected_close_date)->startOfDay() : null;
                        $now = now()->startOfDay();

                        $totalDays = $closesAt ? $createdAt->diffInDays($closesAt) : null;
                        $remainingDays = $closesAt ? $now->diffInDays($closesAt, false) : null;
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 my-6 pt-6 border-t border-white/10">
                        <div>
                            <h3 class="text-sm font-semibold text-light-text-muted">Fecha de Inicio</h3>
                            <p class="text-lg text-light-text">{{ $createdAt->format('d M, Y') }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-light-text-muted">Fecha de Cierre Prevista</h3>
                            <p class="text-lg text-light-text">{{ $closesAt ? $closesAt->format('d M, Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-light-text-muted">Duración Total</h3>
                            <p class="text-lg text-light-text">{{ is_numeric($totalDays) ? $totalDays . ' días' : 'N/A' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-light-text-muted">Días Restantes</h3>
                            @if(is_numeric($remainingDays))
                                @if($remainingDays >= 0)
                                    <p class="text-lg text-green-400">{{ $remainingDays }} días</p>
                                @else
                                    <p class="text-lg text-red-400">Vencido hace {{ abs($remainingDays) }} días</p>
                                @endif
                            @else
                                <p class="text-lg text-light-text">N/A</p>
                            @endif
                        </div>
                    </div>
                    

                    <h3 class="text-lg font-bold text-aurora-cyan mt-8 mb-4">Notas del Deal</h3>
                    <div class="space-y-4">
                        @if ($deal->notes_contact)
                        <div>
                            <h4 class="font-semibold text-light-text-muted">Contacto Inicial</h4>
                            <div class="prose prose-invert max-w-none mt-1 p-4 bg-gray-800 rounded-md">{!! nl2br(e($deal->notes_contact)) !!}</div>
                        </div>
                        @endif
                        @if ($deal->notes_proposal)
                        <div>
                            <h4 class="font-semibold text-light-text-muted">Propuesta Enviada</h4>
                            <div class="prose prose-invert max-w-none mt-1 p-4 bg-gray-800 rounded-md">{!! nl2br(e($deal->notes_proposal)) !!}</div>
                        </div>
                        @endif
                        @if ($deal->notes_negotiation)
                        <div>
                            <h4 class="font-semibold text-light-text-muted">Negociación</h4>
                            <div class="prose prose-invert max-w-none mt-1 p-4 bg-gray-800 rounded-md">{!! nl2br(e($deal->notes_negotiation)) !!}</div>
                        </div>
                        @endif
                        @if (!$deal->notes_contact && !$deal->notes_proposal && !$deal->notes_negotiation)
                            <p class="text-light-text-muted">No hay notas para este deal.</p>
                        @endif
                    </div>

                    <h3 class="text-lg font-bold text-aurora-cyan mt-8 mb-4">Historial de Actividades</h3>

                    <div class="space-y-8">
                        @forelse ($groupedActivities as $stageId => $activities)
                            <div class="p-4 bg-gray-800/50 rounded-lg">
                                <h4 class="font-bold text-aurora-yellow mb-4">{{ $dealStages[$stageId]->name ?? 'General' }}</h4>
                                <div class="space-y-6">
                                    @foreach ($activities as $activity)
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
                                    @endforeach
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

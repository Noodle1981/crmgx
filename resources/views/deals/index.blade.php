<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pipeline de Ventas') }}
            </h2>
            <a href="{{ route('deals.create') }}">
                <x-primary-button>
                    <i class="fas fa-plus mr-2"></i>
                    Crear Nuevo Deal
                </x-primary-button>
            </a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
            <x-session-status :status="session('success')" />
        </div>
    @endif

    <div class="py-12" x-data="{ isActivityModalOpen: false, currentDealId: null, currentDealName: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">
            @foreach ($pipelineData as $stage)
                <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-8">
                    <div class="flex items-center mb-6">
                        <i class="fas fa-layer-group text-2xl text-aurora-cyan mr-3"></i>
                        <h3 class="font-headings font-bold text-2xl text-primary-dark">{{ $stage['name'] }}</h3>
                    </div>
                    <div class="grid grid-cols-1 gap-8">
                        @forelse ($stage['deals'] as $deal)
                            <div class="bg-gray-50 border border-gray-200 rounded-xl shadow-md p-8 min-h-[220px] w-full">
                                <div class="flex justify-between items-start">
                                    <div class="flex-grow">
                                        <p class="font-semibold text-2xl text-primary-dark">{{ $deal->name }}</p>
                                        <p class="text-base text-gray-600">{{ $deal->client->name }}</p>
                                        
                                        @if($deal->value)
                                            @if($stage['name'] === 'Propuesta Enviada')
                                                <p class="text-lg font-bold mt-2 text-aurora-cyan">${{ number_format($deal->value, 0) }}</p>
                                            @elseif($stage['name'] === 'Negociación')
                                                <div class="text-lg font-bold mt-2">
                                                    <span class="text-aurora-cyan">${{ number_format($deal->value, 0) }}</span>
                                                    @if(!is_null($deal->original_value) && $deal->original_value != $deal->value)
                                                        <span class="text-xs font-normal text-gray-400 line-through ml-2">${{ number_format($deal->original_value, 0) }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                        @endif
                                        
                                        @if ($deal->notes_contact || $deal->notes_proposal || $deal->notes_negotiation)
                                            <div class="mt-4 pt-3 border-t border-gray-200 text-sm">
                                                <div class="space-y-4">
                                                    @if ($deal->notes_contact)
                                                    <div class="bg-black/90 rounded-lg p-4 text-white">
                                                        <span class="font-bold text-aurora-cyan">Notas: Contacto Inicial</span>
                                                        <p class="mt-2">{{ $deal->notes_contact }}</p>
                                                    </div>
                                                    @endif
                                                    @if ($deal->notes_proposal)
                                                    <div class="bg-black/90 rounded-lg p-4 text-white">
                                                        <span class="font-bold text-aurora-cyan">Notas: Propuesta Enviada</span>
                                                        <p class="mt-2">{{ $deal->notes_proposal }}</p>
                                                    </div>
                                                    @endif
                                                    @if ($deal->notes_negotiation)
                                                    <div class="bg-black/90 rounded-lg p-4 text-white">
                                                        <span class="font-bold text-aurora-cyan">Notas: Negociación</span>
                                                        <p class="mt-2">{{ $deal->notes_negotiation }}</p>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        <div class="mt-3 pt-2 border-t border-white/10 text-xs">
                                            <div class="flex justify-between">
                                                <span class="font-bold text-white/80">Fecha Inicio:</span>
                                                <x-smart-date :date="$deal->created_at" format="d M, Y" />
                                            </div>
                                            <div class="flex justify-between mt-1">
                                                <span class="font-bold text-white/80">Fecha Cierre:</span>
                                                <x-smart-date :date="$deal->expected_close_date" with-color="true" format="d M, Y" />
                                            </div>
                                        </div>

                                        @if ($deal->activity_summary->isNotEmpty())
                                            <div class="mt-3 pt-2 border-t border-white/10 text-xs">
                                                <p class="font-bold text-white/90 mb-2">Resumen de Actividades:</p>
                                                @foreach ($deal->activity_summary as $stageName => $activityCounts)
                                                    <p class="font-semibold text-white/80">{{ $stageName }}:</p>
                                                    <ul class="list-disc list-inside ml-2">
                                                        @foreach ($activityCounts as $type => $count)
                                                            <li class="text-white/70">{{ ucfirst($type) }}: {{ $count }}</li>
                                                        @endforeach
                                                    </ul>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if ($deal->activities->count())
                                            <div class="mt-6">
                                                <h4 class="font-bold text-aurora-cyan mb-2">Tareas</h4>
                                                <div class="space-y-3">
                                                    @foreach ($deal->activities as $activity)
                                                        <div class="bg-gray-900/80 rounded-lg p-3 text-white">
                                                            <div class="flex justify-between items-center mb-1">
                                                                <span class="text-xs font-semibold text-aurora-yellow">{{ ucfirst($activity->type) }}</span>
                                                                <span class="text-xs text-light-text-muted">{{ $activity->user->name ?? 'Usuario' }}</span>
                                                            </div>
                                                            <div class="flex items-center mb-1">
                                                                <span class="px-2 py-1 rounded text-xs font-bold"
                                                                    @class([
                                                                        'bg-aurora-yellow text-black' => $activity->status === 'pendiente',
                                                                        'bg-aurora-cyan text-black' => $activity->status === 'en espera',
                                                                        'bg-emerald-500 text-white' => $activity->status === 'completada',
                                                                    ])>
                                                                    {{ ucfirst($activity->status) }}
                                                                </span>
                                                            </div>
                                                            <p class="text-sm">{{ $activity->description }}</p>
                                                            <span class="text-xs text-light-text-muted">{{ $activity->created_at->diffForHumans() }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                    <div class="flex flex-col items-end ml-4 space-y-2">
                                        <a href="{{ route('deals.show', $deal) }}" class="text-aurora-cyan hover:text-aurora-cyan/80 transition" title="Ver detalles">
                                            <i class="fas fa-eye"></i> <span class="ml-1">Ver</span>
                                        </a>
                                        <a href="{{ route('deals.edit', $deal) }}" class="text-aurora-cyan hover:text-aurora-cyan/80 transition" title="Editar">
                                            <i class="fas fa-pen"></i> <span class="ml-1">Editar</span>
                                        </a>
                                        <a href="#" @click.prevent="isActivityModalOpen = true; currentDealId = {{ $deal->id }}; currentDealName = '{{ $deal->name }}'" class="text-aurora-cyan hover:text-aurora-cyan/80 transition" title="Agregar tarea">
                                            <i class="fas fa-tasks"></i> <span class="ml-1">Tarea</span>
                                        </a>
                                        <form action="{{ route('deals.destroy', $deal) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este deal?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 transition flex items-center" title="Eliminar">
                                                <i class="fas fa-trash"></i> <span class="ml-1">Eliminar</span>
                                            </button>
                                        </form>
                                        @if($stage['name'] !== 'Propuesta Enviada')
                                            <form action="{{ route('deals.update', $deal) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="deal_stage_id" value="{{ $pipelineData->firstWhere('name', 'Propuesta Enviada')['id'] ?? '' }}">
                                                <button type="submit" class="text-aurora-cyan hover:text-aurora-cyan/80 transition flex items-center" title="Mover a Propuesta Enviada">
                                                    <i class="fas fa-paper-plane"></i> <span class="ml-1">Propuesta Enviada</span>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-light-text-muted py-8">No hay deals en esta etapa.</p>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Activity Modal -->
        <div x-show="isActivityModalOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" 
             style="display: none;"
             @click.away="isActivityModalOpen = false">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 mx-4" @click.stop>
                <h3 class="text-lg font-bold text-primary-dark mb-4">Añadir Actividad a <span x-text="currentDealName"></span></h3>
                
                <form :action="'/deals/' + currentDealId + '/activities'" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <x-input-label for="type" value="Tipo de Actividad" />
                            <select name="type" id="type" class="mt-1 block w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm">
                                <option value="note">Nota</option>
                                <option value="call">Llamada</option>
                                <option value="meeting">Reunión</option>
                                <option value="email">Email</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="status" value="Estado de la tarea" />
                            <select name="status" id="status" class="mt-1 block w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm">
                                <option value="pendiente">Pendiente</option>
                                <option value="en espera">En espera</option>
                                <option value="completada">Completada</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="description" value="Descripción" />
                            <textarea name="description" id="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm" required></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-4">
                        <x-secondary-button @click.prevent="isActivityModalOpen = false">Cancelar</x-secondary-button>
                        <x-primary-button type="submit">Guardar Actividad</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

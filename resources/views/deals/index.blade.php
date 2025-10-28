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
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($pipelineData as $stage)
                    <div class="bg-gray-100 rounded-2xl flex flex-col">
                        {{-- Header de la columna --}}
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="font-headings font-bold text-lg text-primary-dark text-center">{{ $stage['name'] }}</h3>
                        </div>
                        
                        {{-- Contenedor de las tarjetas de deal --}}
                        <div class="p-4 space-y-4 overflow-y-auto flex-grow">
                            @forelse ($stage['deals'] as $deal)
                                
                            <div class="bg-primary border border-primary-dark/20 rounded-lg shadow-md overflow-hidden text-white">
                                    <!-- Parte 1: Contenido Principal -->
                                    <div class="p-4">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-grow">
                                                <p class="font-semibold text-lg text-white">{{ $deal->name }}</p>
                                                <p class="text-s text-white/90">{{ $deal->client->name }}</p>
                                                
                                                @if($deal->value)
    @if($stage['name'] === 'Propuesta Enviada')
        <p class="text-sm font-bold mt-2">${{ number_format($deal->value, 0) }}</p>
    @elseif($stage['name'] === 'Negociación')
        <div class="text-sm font-bold mt-2">
            <span>${{ number_format($deal->value, 0) }}</span>
            @if(!is_null($deal->original_value) && $deal->original_value != $deal->value)
                <span class="text-xs font-normal text-white/70 line-through ml-2">${{ number_format($deal->original_value, 0) }}</span>
            @endif
        </div>
    @endif
@endif
                                                
                                                @if ($deal->notes)
                <div class="mt-3 pt-2 border-t border-white/10 text-xs text-white/80">
                    <p class="font-bold mb-1">Notas:</p>
                    <p class="whitespace-pre-wrap">{{ $deal->notes }}</p>
                </div>
                @endif

                @php
                $activityCounts = $deal->activities->groupBy('type')->map->count();
                $activityIcons = [
                    'note' => 'fa-sticky-note',
                    'call' => 'fa-phone',
                    'meeting' => 'fa-users',
                    'email' => 'fa-envelope',
                ];
                @endphp

                @if($deal->activities->isNotEmpty())
                <div class="mt-3 pt-2 border-t border-white/10 text-xs text-white/80">
                    <div class="space-y-1">
                    @foreach ($activityCounts as $type => $count)
                        <div class="flex items-center justify-between">
                            <span class="capitalize flex items-center">
                                <i class="fas {{ $activityIcons[$type] ?? 'fa-question-circle' }} mr-2 w-4 text-center"></i>
                                <span>{{ ucfirst(__($type)) }}s</span>
                            </span>
                            <span class="font-semibold">{{ $count }}</span>
                        </div>
                    @endforeach
                    </div>
                </div>
                @endif

                                                <div class="mt-2">
                                                    <x-smart-date :date="$deal->expected_close_date" with-color="true" with-icon="true" format="d M" />
                                                </div>
                                            </div>
                                            <div class="flex items-center ml-4 space-x-4">
                                                <a href="{{ route('deals.show', $deal) }}" class="text-white/70 hover:text-white transition" title="Ver Deal">
                                                    <i class="fas fa-eye text-xl"></i>
                                                </a>

                                                <x-dropdown align="right" width="48">
                                                    <x-slot name="trigger">
                                                        <button class="text-white/70 hover:text-white transition"><i class="fas fa-ellipsis-v"></i></button>
                                                    </x-slot>
                                                    <x-slot name="content">
                                                        <x-dropdown-link href="#" @click.prevent="isActivityModalOpen = true; currentDealId = {{ $deal->id }}; currentDealName = '{{ addslashes($deal->name) }}'">
                                                            Añadir Actividad
                                                        </x-dropdown-link>
                                                        <x-dropdown-link :href="route('deals.edit', $deal)">Editar</x-dropdown-link>
                                                        <form action="{{ route('deals.destroy', $deal) }}" method="POST" onsubmit="return confirm('¿Estás seguro?');">@csrf @method('DELETE')<button type="submit" class="block w-full px-4 py-2 text-start text-sm leading-5 text-red-600 hover:text-red-800 hover:bg-gray-100">Eliminar</button></form>
                                                    </x-slot>
                                                </x-dropdown>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Parte 2: Barra de Acciones -->
                                    <div class="px-4 py-2 bg-primary-light flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            @if ($deal->deal_stage_id > 1)
                                                <form action="{{ route('deals.updateStage', $deal) }}" method="POST">@csrf @method('PATCH')<input type="hidden" name="deal_stage_id" value="{{ $deal->deal_stage_id - 1 }}"><button type="submit" class="text-primary-dark/70 hover:text-primary-dark transition text-lg" title="Mover a etapa anterior"><i class="fas fa-chevron-left"></i></button></form>
                                            @endif
                                            @if ($deal->deal_stage_id < $pipelineData->count())
                                                <form action="{{ route('deals.updateStage', $deal) }}" method="POST">@csrf @method('PATCH')<input type="hidden" name="deal_stage_id" value="{{ $deal->deal_stage_id + 1 }}"><button type="submit" class="text-primary-dark/70 hover:text-primary-dark transition text-lg" title="Mover a etapa siguiente"><i class="fas fa-chevron-right"></i></button></form>
                                            @endif
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <form action="{{ route('deals.lost', $deal) }}" method="POST">@csrf @method('PATCH')<button type="submit" class="text-red-500 hover:text-red-700 transition text-lg" title="Marcar como Perdido"><i class="fas fa-times-circle"></i></button></form>
                                            <form action="{{ route('deals.win', $deal) }}" method="POST">@csrf @method('PATCH')<button type="submit" class="text-green-500 hover:text-green-700 transition text-lg" title="Marcar como Ganado"><i class="fas fa-check-circle"></i></button></form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-text-muted py-10"><i class="fas fa-folder-open text-3xl mb-2"></i><p class="text-sm">No hay deals en esta etapa.</p></div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
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

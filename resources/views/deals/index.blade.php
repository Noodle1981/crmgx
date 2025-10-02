<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <span>{{ __('Pipeline de Ventas') }}</span>
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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($pipelineData as $stage)
                    <div class="bg-gray-900/60 backdrop-blur-xl border border-white/10 rounded-2xl flex flex-col">
                        <div class="p-4 border-b border-white/10">
                            <h3 class="font-headings font-bold text-lg text-aurora-cyan text-center">{{ $stage['name'] }}</h3>
                        </div>
                        
                        <div class="p-4 space-y-4 overflow-y-auto flex-grow">
                            @forelse ($stage['deals'] as $deal)
                                <div class="bg-gray-800/70 border border-white/5 rounded-lg shadow-lg overflow-hidden">
                                    <!-- Parte 1: Contenido Principal -->
                                    <div class="p-4">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-grow">
                                                <p class="font-semibold text-light-text">{{ $deal->name }}</p>
                                                <p class="text-sm text-light-text-muted">{{ $deal->client->name }}</p>
                                                @if($deal->value)
                                                    <p class="text-sm font-bold text-aurora-cyan mt-2">${{ number_format($deal->value, 0) }}</p>
                                                @endif
                                                
                                                <div class="mt-2">
                                                    <x-smart-date :date="$deal->expected_close_date" with-color="true" with-icon="true" format="d M" />
                                                </div>
                                            </div>
                                            <div class="flex items-center ml-4 space-x-4">
                                                @if($deal->activities_count > 0)
                                                    <a href="{{ route('deals.show', $deal) }}" class="text-gray-400 hover:text-aurora-cyan transition" title="Ver Actividades ({{ $deal->activities_count }})">
                                                        <i class="fas fa-search text-xl"></i>
                                                    </a>
                                                @else
                                                    <span class="text-gray-600 cursor-not-allowed" title="No hay actividades">
                                                        <i class="fas fa-search text-xl"></i>
                                                    </span>
                                                @endif

                                                <x-dropdown align="right" width="48">
                                                    <x-slot name="trigger">
                                                        <button class="text-light-text-muted hover:text-light-text transition"><i class="fas fa-ellipsis-v"></i></button>
                                                    </x-slot>
                                                    <x-slot name="content">
                                                        <x-dropdown-link href="#" @click.prevent="isActivityModalOpen = true; currentDealId = {{ $deal->id }}; currentDealName = '{{ addslashes($deal->name) }}'">
                                                            Añadir Actividad
                                                        </x-dropdown-link>
                                                        <x-dropdown-link :href="route('deals.edit', $deal)">Editar</x-dropdown-link>
                                                        <form action="{{ route('deals.destroy', $deal) }}" method="POST" onsubmit="return confirm('¿Estás seguro?');">@csrf @method('DELETE')<button type="submit" class="block w-full px-4 py-2 text-start text-sm leading-5 text-aurora-red-pop/80 hover:text-aurora-red-pop hover:bg-gray-800/50">Eliminar</button></form>
                                                    </x-slot>
                                                </x-dropdown>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Parte 2: Barra de Acciones -->
                                    <div class="px-4 py-2 bg-black/20 flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            @if ($deal->deal_stage_id > 1)
                                                <form action="{{ route('deals.updateStage', $deal) }}" method="POST">@csrf @method('PATCH')<input type="hidden" name="deal_stage_id" value="{{ $deal->deal_stage_id - 1 }}"><button type="submit" class="text-light-text-muted hover:text-aurora-cyan transition text-lg" title="Mover a etapa anterior"><i class="fas fa-chevron-left"></i></button></form>
                                            @endif
                                            @if ($deal->deal_stage_id < $pipelineData->count())
                                                <form action="{{ route('deals.updateStage', $deal) }}" method="POST">@csrf @method('PATCH')<input type="hidden" name="deal_stage_id" value="{{ $deal->deal_stage_id + 1 }}"><button type="submit" class="text-light-text-muted hover:text-aurora-cyan transition text-lg" title="Mover a etapa siguiente"><i class="fas fa-chevron-right"></i></button></form>
                                            @endif
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <form action="{{ route('deals.lost', $deal) }}" method="POST">@csrf @method('PATCH')<button type="submit" class="text-light-text-muted hover:text-aurora-red-pop transition text-lg" title="Marcar como Perdido"><i class="fas fa-times-circle"></i></button></form>
                                            <form action="{{ route('deals.win', $deal) }}" method="POST">@csrf @method('PATCH')<button type="submit" class="text-green-400 hover:text-white transition text-lg" title="Marcar como Ganado"><i class="fas fa-check-circle"></i></button></form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-light-text-muted py-10"><i class="fas fa-folder-open text-3xl mb-2"></i><p class="text-sm">No hay deals en esta etapa.</p></div>
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
            <div class="bg-gray-900/80 backdrop-blur-xl border border-white/10 rounded-2xl shadow-xl w-full max-w-md p-6 mx-4" @click.stop>
                <h3 class="text-lg font-bold text-aurora-cyan mb-4">Añadir Actividad a <span x-text="currentDealName"></span></h3>
                
                <form :action="`/deals/${currentDealId}/activities`" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="type" class="block text-sm font-medium text-light-text-muted">Tipo de Actividad</label>
                            <select name="type" id="type" class="mt-1 block w-full bg-gray-800/70 border-white/10 rounded-md shadow-sm text-light-text focus:ring-aurora-cyan focus:border-aurora-cyan">
                                <option value="note">Nota</option>
                                <option value="call">Llamada</option>
                                <option value="meeting">Reunión</option>
                                <option value="email">Email</option>
                            </select>
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-light-text-muted">Descripción</label>
                            <textarea name="description" id="description" rows="4" class="mt-1 block w-full bg-gray-800/70 border-white/10 rounded-md shadow-sm text-light-text focus:ring-aurora-cyan focus:border-aurora-cyan" required></textarea>
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

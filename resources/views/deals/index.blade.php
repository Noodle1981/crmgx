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

    <div class="py-12">
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
                                            <div>
                                                <p class="font-semibold text-light-text">{{ $deal->name }}</p>
                                                <p class="text-sm text-light-text-muted">{{ $deal->client->name }}</p>
                                                @if($deal->value)
                                                    <p class="text-sm font-bold text-aurora-cyan mt-2">${{ number_format($deal->value, 0) }}</p>
                                                @endif
                                                
                                                {{-- ========================================================== --}}
                                                {{-- AQUÍ AÑADIMOS LA FECHA INTELIGENTE --}}
                                                {{-- ========================================================== --}}
                                                <div class="mt-2">
                                                    <x-smart-date :date="$deal->expected_close_date" with-color="true" with-icon="true" format="d M" />
                                                </div>
                                            </div>
                                            <x-dropdown align="right" width="48">
                                                <x-slot name="trigger">
                                                    <button class="text-light-text-muted hover:text-light-text transition -mr-2"><i class="fas fa-ellipsis-v"></i></button>
                                                </x-slot>
                                                <x-slot name="content">
                                                    <x-dropdown-link :href="route('deals.edit', $deal)">Editar</x-dropdown-link>
                                                    <form action="{{ route('deals.destroy', $deal) }}" method="POST" onsubmit="return confirm('¿Estás seguro?');">@csrf @method('DELETE')<button type="submit" class="block w-full px-4 py-2 text-start text-sm leading-5 text-aurora-red-pop/80 hover:text-aurora-red-pop hover:bg-gray-800/50">Eliminar</button></form>
                                                </x-slot>
                                            </x-dropdown>
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
    </div>
</x-app-layout>
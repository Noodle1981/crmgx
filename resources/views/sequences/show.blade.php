<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <a href="{{ route('sequences.index') }}" class="text-sm text-light-text-muted hover:text-aurora-cyan">&larr; Volver a Secuencias</a>
                <h2 class="font-headings font-bold text-2xl text-light-text mt-1">Secuencia: {{ $sequence->name }}</h2>
            </div>
            <a href="{{ route('sequences.steps.create', $sequence) }}">
                <x-primary-button>
                    <i class="fas fa-plus mr-2"></i>
                    Añadir Paso
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
            
            <x-card>
                <x-slot name="header">
                    <h3 class="font-headings text-xl">Pasos de la Secuencia</h3>
                </x-slot>
                
                @forelse($sequence->steps as $step)
                    <div class="border-b border-white/10 last:border-b-0 p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-bold text-light-text">
                                    Paso {{ $step->order }}: 
                                    <span class="font-semibold">
                                            @if($step->type == 'email')
                                                <i class="fas fa-envelope text-aurora-cyan mr-1"></i> Enviar Email
                                            @elseif($step->type == 'call')
                                                <i class="fas fa-phone text-aurora-green mr-1"></i> Llamada Telefónica
                                            @elseif($step->type == 'video_call')
                                                <i class="fas fa-video text-aurora-purple mr-1"></i> Videollamada
                                            @else
                                                <i class="fas fa-check-square text-aurora-yellow mr-1"></i> Crear Tarea
                                            @endif
                                    </span>
                                    <span class="text-sm font-normal text-light-text-muted">(después de {{ $step->delay_days }} días)</span>
                                </p>
                                @if($step->subject)
                                    <p class="text-sm text-light-text-muted mt-1"><strong>Asunto:</strong> {{ $step->subject }}</p>
                                @endif
                                <p class="text-sm text-light-text-muted mt-1"><strong>Contenido:</strong> {{ $step->body }}</p>
                            </div>
                            
                            <div class="text-sm space-x-4 flex-shrink-0 ml-4">
                                <a href="{{ route('sequences.steps.edit', [$sequence, $step]) }}" class="text-light-text-muted hover:text-aurora-cyan transition" title="Editar"><i class="fas fa-pencil-alt"></i></a>
                                <form action="{{ route('sequences.steps.destroy', [$sequence, $step]) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro que quieres eliminar este paso?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-light-text-muted hover:text-aurora-red-pop transition" title="Eliminar"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-light-text-muted py-10">
                        <i class="fas fa-stream text-4xl mb-3"></i>
                        <p>Esta secuencia aún no tiene pasos.</p>
                    </div>
                @endforelse
            </x-card>
        </div>
    </div>
</x-app-layout>
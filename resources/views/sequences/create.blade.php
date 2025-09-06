<x-app-layout>
    <x-slot name="header">
        Crear Nueva Secuencia
    </x-slot>
    
    <x-card class="max-w-4xl mx-auto">
        {{-- Envolvemos el formulario en un div de Alpine.js --}}
        <div x-data="{}">
            <form action="{{ route('sequences.store') }}" method="POST">
                @csrf
                
                {{-- ========================================================== --}}
                {{-- INICIO DE LA NUEVA SECCIÓN DE SUGERENCIAS --}}
                {{-- ========================================================== --}}
                <div class="mb-8 p-4 border border-white/10 rounded-lg bg-gray-800/50">
                    <h4 class="font-semibold text-light-text mb-3">Sugerencias de Plantillas (Opcional)</h4>
                    <div class="flex flex-wrap gap-2">
                        {{-- Cada botón es una plantilla rápida --}}
                        <button type="button" @click="$refs.sequenceNameInput.value = 'Seguimiento Post-Reunión en '; $refs.sequenceNameInput.focus()"
                                class="px-3 py-1 bg-aurora-cyan/10 text-aurora-cyan text-sm font-semibold rounded-full hover:bg-aurora-cyan/20 transition">
                            + Reunión presencial en
                        </button>
                        <button type="button" @click="$refs.sequenceNameInput.value = 'Llamada de seguimiento a '; $refs.sequenceNameInput.focus()"
                                class="px-3 py-1 bg-aurora-cyan/10 text-aurora-cyan text-sm font-semibold rounded-full hover:bg-aurora-cyan/20 transition">
                            + Llamada de seguimiento a
                        </button>
                        <button type="button" @click="$refs.sequenceNameInput.value = 'Demo del producto para '; $refs.sequenceNameInput.focus()"
                                class="px-3 py-1 bg-aurora-cyan/10 text-aurora-cyan text-sm font-semibold rounded-full hover:bg-aurora-cyan/20 transition">
                            + Demo del producto para
                        </button>
                    </div>
                </div>
                {{-- ========================================================== --}}
                {{-- FIN DE LA NUEVA SECCIÓN --}}
                {{-- ========================================================== --}}

                @include('sequences._form', [
                    'sequence' => new \App\Models\Sequence,
                    'btnText' => 'Guardar y Añadir Pasos'
                ])
            </form>
        </div>
    </x-card>
</x-app-layout>
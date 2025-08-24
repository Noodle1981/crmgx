<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Secuencia: {{ $sequence->name }}
            </h2>
            <a href="{{ route('sequences.steps.create', $sequence) }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded-md">
            + Añadir Paso
        </a>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Pasos de la Secuencia</h3>
                    @forelse($sequence->steps as $step)
                        <div class="border p-3 rounded-md mb-2 dark:border-gray-700">
                            <p><strong>Paso {{ $step->order }}:</strong> {{ $step->type == 'email' ? 'Enviar Email' : 'Crear Tarea' }} ({{ $step->delay_days }} días después)</p>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">{{ $step->body }}</p>
                        </div>
                    @empty
                        <p>Esta secuencia aún no tiene pasos.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
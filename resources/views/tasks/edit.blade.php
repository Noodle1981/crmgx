<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Detalle de la Tarea
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold">{{ $task->title }}</h3>
                    <p class="mt-2"><strong>Fecha de Vencimiento:</strong> {{ $task->due_date->format('d/m/Y') }}</p>
                    <p class="mt-2"><strong>Estado:</strong> <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ $task->status }}</span></p>
                    
                    @if($task->description)
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-gray-600 dark:text-gray-400">{{ $task->description }}</p>
                        </div>
                    @endif

                    <div class="mt-6">
                        <a href="{{ url()->previous() }}" class="text-indigo-600 hover:text-indigo-900">
                            &larr; Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Detalles de la Tarea
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium">{{ $task->title }}</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ $task->description }}</p>

                    <div class="mt-4">
                        <p><strong>Vence:</strong> {{ $task->due_date->format('d/m/Y') }}</p>
                        <p><strong>Estado:</strong> {{ $task->status }}</p>
                        @if ($task->taskable)
                            <p><strong>Relacionado con:</strong> {{ $task->taskable->name }} ({{ class_basename($task->taskable_type) }})</p>
                        @endif
                    </div>

                    <div class="mt-6">
                        <form action="{{ route('tasks.update', $task) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-4">
                                <label for="video_link" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Enlace de Videollamada</label>
                                <input type="text" name="video_link" id="video_link" value="{{ old('video_link', $task->video_link) }}" class="mt-1 block w-full bg-gray-900/60 border border-white/10 rounded-lg text-light-text transition-all duration-300 focus:border-aurora-cyan focus:ring-2 focus:ring-aurora-cyan/40 focus:outline-none">
                            </div>
                            <button type="submit" class="ml-4 inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Guardar Enlace
                            </button>
                        </form>
                    </div>

                    <div class="mt-6">
                        @php
                            $phone = null;
                            $name = null;
                            if ($task->taskable) {
                                if ($task->taskable_type === 'App\\Models\\Contact' || $task->taskable_type === 'App\\Models\\Lead') {
                                    $phone = $task->taskable->phone;
                                    $name = $task->taskable->name;
                                } elseif ($task->taskable_type === 'App\\Models\\Deal' && $task->taskable->contact) {
                                    $phone = $task->taskable->contact->phone;
                                    $name = $task->taskable->contact->name;
                                }
                            }
                        @endphp

                        @if($phone)
                            @php
                                $phoneDigits = preg_replace('/\D+/', '', $phone);
                                $waMessage = rawurlencode("Hola {$name}, sobre la tarea: {$task->title}");
                                $waUrl = "https://wa.me/{$phoneDigits}?text={$waMessage}";
                            @endphp
                            <a href="{{ $waUrl }}" target="_blank" class="text-green-600 hover:text-green-900" title="Enviar WhatsApp">
                                <i class="fab fa-whatsapp fa-lg"></i> Enviar WhatsApp
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

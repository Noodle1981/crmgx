<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Inscribir a {{ $contact->name }} en una Secuencia
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('contacts.enroll.store', $contact) }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <p>Estás a punto de inscribir a <strong>{{ $contact->name }}</strong> (de {{ $contact->client->name }}) en una secuencia de seguimiento automático.</p>
                        </div>

                        <div class="mb-6">
                            <label for="sequence_id" class="block text-sm font-bold mb-2 dark:text-gray-300">Selecciona la Secuencia:</label>
                            <select name="sequence_id" id="sequence_id" class="shadow rounded w-full py-2 px-3 dark:bg-gray-900 dark:text-gray-300" required>
                                <option value="">-- Elige una plantilla --</option>
                                @foreach ($sequences as $sequence)
                                    <option value="{{ $sequence->id }}">{{ $sequence->name }}</option>
                                @endforeach
                            </select>
                            @error('sequence_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('clients.show', $contact->client) }}" class="dark:text-gray-400 hover:underline mr-4">Cancelar</a>
                            <button type="submit" class="px-4 py-2 bg-green-500 hover:bg-green-700 text-white font-bold rounded-md">
                                Iniciar Secuencia
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
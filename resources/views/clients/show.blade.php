<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Detalles del Cliente: {{ $client->name }}
            </h2>
            <a href="{{ route('clients.edit', $client) }}" class="px-4 py-2 bg-indigo-500 hover:bg-indigo-700 text-white font-bold rounded-md">
                Editar Cliente
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Columna Izquierda: Detalles y Contactos -->
            <div class="md:col-span-1 space-y-6">
                <!-- Tarjeta de Detalles del Cliente -->
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold border-b pb-3 mb-3 text-gray-800 dark:text-gray-200">Información</h3>
                    <p class="text-gray-600 dark:text-gray-400"><strong>Compañía:</strong> {{ $client->company ?? 'N/A' }}</p>
                    <p class="text-gray-600 dark:text-gray-400"><strong>Email:</strong> {{ $client->email ?? 'N/A' }}</p>
                    <p class="text-gray-600 dark:text-gray-400"><strong>Teléfono:</strong> {{ $client->phone ?? 'N/A' }}</p>
                    @if($client->notes)
                        <p class="mt-4 text-gray-600 dark:text-gray-400"><strong>Notas:</strong></p>
                        <p class="text-gray-500 dark:text-gray-300">{{ $client->notes }}</p>
                    @endif
                </div>

                <!-- Tarjeta de Contactos -->
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-center border-b pb-3 mb-3">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Contactos</h3>
                        <a href="{{ route('clients.contacts.create', $client) }}" class="text-sm px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">+ Añadir</a>
                    </div>
                    @forelse($client->contacts as $contact)
                        <div class="py-2 border-b dark:border-gray-700 last:border-b-0">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $contact->name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $contact->position }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $contact->email }}</p>
                                </div>
                                <div class="text-xs space-x-2 flex-shrink-0 ml-2">
                                    <a href="{{ route('contacts.enroll.create', $contact) }}" class="font-bold text-green-500 hover:underline">Inscribir</a>
                                    <a href="{{ route('clients.contacts.edit', [$client, $contact]) }}" class="text-indigo-500 hover:underline">Editar</a>
                                    <form action="{{ route('clients.contacts.destroy', [$client, $contact]) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline">Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                          @foreach ($contact->sequenceEnrollments->where('status', 'active') as $enrollment)
                <p class="text-xs text-green-600 dark:text-green-400 font-semibold mt-1">
                    ► Inscrito en: {{ $enrollment->sequence->name }}
                </p>
            @endforeach


                    @empty
                        <p class="text-gray-500 dark:text-gray-400">No hay contactos para este cliente.</p>
                    @endforelse
                </div>
            </div>

            <!-- Columna Derecha: Deals y Actividades -->
            <div class="md:col-span-2 space-y-6">
                <!-- Tarjeta de Deals -->
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-center border-b pb-3 mb-3">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Oportunidades (Deals)</h3>
                        <a href="{{ route('clients.deals.create', $client) }}" class="text-sm px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">+ Añadir</a>
                    </div>
                    @forelse($client->deals as $deal)
                        <div class="py-2 border-b dark:border-gray-700 last:border-b-0">
                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $deal->name }} - <span class="text-sm font-bold">${{ number_format($deal->value ?? 0, 2) }}</span></p>
                            <!-- CORRECCIÓN AQUÍ -->
                            <p class="text-sm text-gray-600 dark:text-gray-400">Etapa: {{ optional($deal->stage)->name }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Estado: 
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($deal->status == 'open') bg-blue-100 text-blue-800 @endif
                                    @if($deal->status == 'won') bg-green-100 text-green-800 @endif
                                    @if($deal->status == 'lost') bg-red-100 text-red-800 @endif
                                ">
                                    {{ ucfirst($deal->status) }}
                                </span>
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400">No hay deals para este cliente.</p>
                    @endforelse
                </div>

                <!-- Tarjeta de Historial de Actividades -->
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold border-b pb-3 mb-3 text-gray-800 dark:text-gray-200">Historial de Actividades</h3>
                    <!-- Aquí irá el mini-formulario para añadir actividades -->
                    @forelse($client->activities as $activity)
                        <div class="py-2 border-b dark:border-gray-700 last:border-b-0">
                            <!-- CORRECCIÓN AQUÍ -->
                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ ucfirst($activity->type) }} por {{ optional($activity->user)->name }} <span class="text-sm text-gray-500 float-right">{{ $activity->created_at->diffForHumans() }}</span></p>
                            <p class="text-gray-600 dark:text-gray-400">{{ $activity->description }}</p>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400">No hay actividades registradas.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
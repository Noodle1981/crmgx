<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">

        
            <span>Detalles del Cliente: {{ $client->name }}</span>
            <a href="{{ route('clients.edit', $client) }}">
                <x-secondary-button>
                    <i class="fas fa-pen mr-2"></i>
                    Editar Cliente
                </x-secondary-button>
            </a>
            <a href="{{ route('clients.index') }}" class="text-light-text-muted hover:text-light-text transition" title="Volver a la lista de clientes">
                    <i class="fas fa-arrow-left text-2xl"></i>
                </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Columna Izquierda: Detalles y Contactos -->
            <div class="lg:col-span-1 space-y-8">
                <!-- Tarjeta de Detalles del Cliente -->
                <x-card>
                    <x-slot name="header">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-info-circle text-xl text-aurora-cyan"></i>
                            <h3 class="font-headings text-xl text-light-text">Información</h3>
                        </div>
                    </x-slot>
                    <div class="space-y-3 text-light-text-muted">
                        <p><strong>Compañía:</strong> <span class="text-light-text">{{ $client->company ?? 'N/A' }}</span></p>
                        <p><strong>Email:</strong> <span class="text-light-text">{{ $client->email ?? 'N/A' }}</span></p>
                        <div class="flex justify-between items-center">
                            <p><strong>Teléfono:</strong> <span class="text-light-text">{{ $client->phone ?? 'N/A' }}</span></p>
                            
                            {{-- ========================================================== --}}
                            {{-- BOTÓN DE WHATSAPP --}}
                            {{-- ========================================================== --}}
                            @if ($client->phone)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $client->phone) }}" target="_blank"
                                   class="text-green-400 hover:text-green-300 transition text-2xl" title="Contactar por WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            @endif
                        </div>
                        @if($client->notes)
                            <div class="pt-3 border-t border-white/10">
                                <p><strong>Notas:</strong></p>
                                <p class="text-light-text whitespace-pre-wrap">{{ $client->notes }}</p>
                            </div>
                        @endif
                    </div>
                </x-card>

                <!-- Tarjeta de Contactos -->
                <x-card>
                    <x-slot name="header">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-users text-xl text-aurora-cyan"></i>
                                <h3 class="font-headings text-xl text-light-text">Contactos</h3>
                            </div>
                            <a href="{{ route('clients.contacts.create', $client) }}"><x-secondary-button type="button" class="!px-3 !py-1 text-xs">+ Añadir</x-secondary-button></a>
                        </div>
                    </x-slot>
                    <div class="space-y-4 divide-y divide-white/10">
                        @forelse($client->contacts as $contact)
                            <div class="pt-4 first:pt-0">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-semibold text-light-text">{{ $contact->name }}</p>
                                        <p class="text-sm text-light-text-muted">{{ $contact->position }}</p>
                                        <p class="text-sm text-light-text-muted">{{ $contact->email }}</p>
                                        @foreach ($contact->sequenceEnrollments->where('status', 'active') as $enrollment)
                                            <p class="text-xs text-green-400 font-semibold mt-1 flex items-center">
                                                <i class="fas fa-robot mr-1.5 animate-pulse"></i>
                                                Inscrito en: {{ $enrollment->sequence->name }}
                                            </p>
                                        @endforeach
                                    </div>
                                    <x-dropdown align="right" width="48">
                                        <x-slot name="trigger"><button class="text-light-text-muted hover:text-light-text transition"><i class="fas fa-ellipsis-v"></i></button></x-slot>
                                        <x-slot name="content">
                                            <form action="{{ route('contacts.enroll.create', $contact) }}" method="GET">
                                                <button type="submit" class="block w-full px-4 py-2 text-start text-sm leading-5 text-green-400 hover:text-green-300 hover:bg-gray-800/50">Inscribir</button>
                                            </form>
                                            <x-dropdown-link :href="route('clients.contacts.edit', [$client, $contact])">Editar</x-dropdown-link>
                                            <form action="{{ route('clients.contacts.destroy', [$client, $contact]) }}" method="POST" onsubmit="return confirm('¿Seguro?');">@csrf @method('DELETE')<button type="submit" class="block w-full px-4 py-2 text-start text-sm leading-5 text-aurora-red-pop/80 hover:text-aurora-red-pop hover:bg-gray-800/50">Eliminar</button></form>
                                        </x-slot>
                                    </x-dropdown>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-light-text-muted py-8">No hay contactos para este cliente.</p>
                        @endforelse
                    </div>
                </x-card>
            </div>

            <!-- Columna Derecha: Deals y Actividades -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Tarjeta de Deals -->
                <x-card>
                    <x-slot name="header">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-handshake text-xl text-aurora-cyan"></i>
                                <h3 class="font-headings text-xl text-light-text">Oportunidades (Deals)</h3>
                            </div>
                            <a href="{{ route('clients.deals.create', $client) }}"><x-secondary-button type="button" class="!px-3 !py-1 text-xs">+ Añadir</x-secondary-button></a>
                        </div>
                    </x-slot>
                    <div class="space-y-4 divide-y divide-white/10">
                        @forelse($client->deals as $deal)
                            <div class="pt-4 first:pt-0">
                                <div class="flex justify-between items-center">
                                    <p class="font-semibold text-light-text">{{ $deal->name }}</p>
                                    <span class="text-sm font-bold text-aurora-cyan">${{ number_format($deal->value ?? 0, 0) }}</span>
                                </div>
                                <div class="flex justify-between items-center mt-1">
                                    <p class="text-sm text-light-text-muted">Etapa: {{ optional($deal->stage)->name }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-light-text-muted py-8">No hay deals para este cliente.</p>
                        @endforelse
                    </div>
                </x-card>
                
                <!-- Tarjeta de Historial de Actividades -->
                <x-card>
                    <x-slot name="header">
                        <h3 class="font-headings text-xl">Historial de Actividades</h3>
                    </x-slot>
                    <div class="space-y-4 divide-y divide-white/10">
                        @forelse($client->activities->sortByDesc('created_at') as $activity)
                            <div class="pt-4 first:pt-0">
                                <div class="flex justify-between items-center text-sm">
                                    <p class="font-semibold text-light-text">{{ ucfirst($activity->type) }} por {{ optional($activity->user)->name }}</p>
                                    <x-smart-date :date="$activity->created_at" human="true" />
                                </div>
                                <p class="text-light-text-muted mt-1">{{ $activity->description }}</p>
                            </div>
                        @empty
                            <p class="text-center text-light-text-muted py-8">No hay actividades registradas.</p>
                        @endforelse
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</x-app-layout>
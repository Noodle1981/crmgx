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
                <!-- Tarjeta de Información Fiscal -->
                <x-card>
                    <x-slot name="header">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-building text-xl text-aurora-cyan"></i>
                            <h3 class="font-headings text-xl text-light-text">Información Fiscal</h3>
                        </div>
                    </x-slot>
                    <div class="space-y-3 text-light-text-muted">
                        <p><strong>Razón Social:</strong> <span class="text-light-text">{{ $client->company ?? 'N/A' }}</span></p>
                        <p><strong>CUIT:</strong> <span class="text-light-text">{{ $client->cuit ?? 'N/A' }}</span></p>
                        <p><strong>Dirección Fiscal:</strong> <span class="text-light-text">{{ $client->fiscal_address_street ?? 'N/A' }}</span></p>
                        <p><strong>Actividad Económica:</strong> <span class="text-light-text">{{ $client->economic_activity ?? 'N/A' }}</span></p>
                        <p><strong>ART:</strong> <span class="text-light-text">{{ $client->art_provider ?? 'N/A' }}</span></p>
                    </div>
                </x-card>

                <!-- Tarjeta de Información de Contacto -->
                <x-card>
                    <x-slot name="header">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-info-circle text-xl text-aurora-cyan"></i>
                            <h3 class="font-headings text-xl text-light-text">Información de Contacto</h3>
                        </div>
                    </x-slot>
                    <div class="space-y-3 text-light-text-muted">
                        <p><strong>Email:</strong> <span class="text-light-text">{{ $client->email ?? 'N/A' }}</span></p>
                        <div class="flex justify-between items-center">
                            <p><strong>Teléfono:</strong> <span class="text-light-text">{{ $client->phone ?? 'N/A' }}</span></p>
                            
@if ($client->phone)
    <a href="https://wa.me/{{ $client->phone }}" target="_blank"
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

                <!-- Tarjeta de Contactos (ACTUALIZADA) -->
<x-card>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <i class="fas fa-users text-xl text-aurora-cyan"></i>
                <h3 class="font-headings text-xl text-light-text">Contactos Activos</h3>
            </div>
        </div>
    </x-slot>
    <div class="space-y-4 divide-y divide-white/10">
        @forelse($client->contacts->where('contact_status', 'activo') as $contact)
            <div class="pt-4 first:pt-0">
                <div class="flex justify-between items-start">
                    {{-- Información del Contacto --}}
                    <div>
                        <p class="font-semibold text-light-text">{{ $contact->name }}</p>
                        <p class="text-sm text-light-text-muted">{{ $contact->position }}</p>
                        @if($contact->establishment)
                            <p class="text-sm text-aurora-cyan">{{ $contact->establishment->name }}</p>
                        @endif
                        <p class="text-sm text-light-text-muted">{{ $contact->email }}</p>
                        @if ($contact->phone)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contact->phone) }}" target="_blank"
                               class="text-green-400 hover:text-green-300 transition text-xl inline-block mt-2" title="Contactar por WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        @endif
                    </div>
                    <div class="flex items-center space-x-4 flex-shrink-0 ml-2">
                        {{-- Acción: Editar Contacto --}}
                        <a href="{{ route('clients.contacts.edit', [$client, $contact]) }}" class="text-light-text-muted hover:text-aurora-cyan transition" title="Editar Contacto">
                            <i class="fas fa-pen"></i>
                        </a>

                        {{-- Acción: Dar de baja Contacto --}}
                        <form action="{{ route('contacts.deactivate', $contact) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro que quieres dar de baja este contacto?');">
                            @csrf
                            <button type="submit" class="text-light-text-muted hover:text-aurora-red-pop transition" title="Dar de baja">
                                <i class="fas fa-user-slash"></i>
                            </button>
                        </form>
                    </div>
                    {{-- ========================================================== --}}
                </div>
            </div>
        @empty
            <p class="text-center text-light-text-muted py-8">No hay contactos activos para este cliente.</p>
        @endforelse
    </div>
</x-card>

<x-card>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <i class="fas fa-users text-xl text-orange-xamanen"></i>
            <h3 class="font-headings text-xl text-orange-xamanen">Contactos Inactivos</h3>
        </div>
    </x-slot>
    <div class="space-y-4 divide-y divide-white/10">
        @forelse($client->contacts->where('contact_status', 'inactivo') as $contact)
            <div class="pt-4 first:pt-0">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-semibold text-light-text">{{ $contact->name }}</p>
                        <p class="text-sm text-light-text-muted">{{ $contact->position }}</p>
                        @if($contact->establishment)
                            <p class="text-sm text-aurora-cyan">{{ $contact->establishment->name }}</p>
                        @endif
                        <p class="text-sm text-light-text-muted">{{ $contact->email }}</p>
                    </div>
                    <div class="flex items-center space-x-4 flex-shrink-0 ml-2">
                        {{-- Acción: Reactivar Contacto --}}
                        <form action="{{ route('contacts.activate', $contact) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro que quieres reactivar este contacto?');">
                            @csrf
                            <button type="submit" class="text-green-600 hover:text-green-800 transition" title="Reactivar">
                                <i class="fas fa-user-check"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-light-text-muted py-8">No hay contactos inactivos para este cliente.</p>
        @endforelse
    </div>
</x-card>
            </div>

            <!-- Columna Derecha: Deals y Actividades -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Tarjeta de Establecimientos -->
                <x-card>
                    <x-slot name="header">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-store-alt text-xl text-aurora-cyan"></i>
                                <h3 class="font-headings text-xl text-light-text">Establecimientos</h3>
                            </div>
                            <a href="{{ route('clients.establishments.create', $client) }}"><x-secondary-button type="button" class="!px-3 !py-1 text-xs">+ Añadir</x-secondary-button></a>
                        </div>
                    </x-slot>
                    <div class="space-y-4 divide-y divide-white/10">
                        @forelse($client->establishments as $establishment)
                            <div class="pt-4 first:pt-0">
                                <div class="flex flex-col md:flex-row justify-between items-start">
                                    <div class="flex-1">
                                        <p class="font-semibold text-light-text text-lg mb-1">{{ $establishment->name }}</p>
                                        @if($establishment->latitude && $establishment->longitude)
                                            <div class="mb-2 rounded overflow-hidden" style="height:180px;">
                                                <div id="map-est-{{ $establishment->id }}" style="height:100%; width:100%;"></div>
                                            </div>
                                            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                                            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                                            <script>
                                                document.addEventListener('DOMContentLoaded', function () {
                                                    var map = L.map('map-est-{{ $establishment->id }}').setView([{{ $establishment->latitude }}, {{ $establishment->longitude }}], 15);
                                                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                                        maxZoom: 19,
                                                        attribution: '© OpenStreetMap'
                                                    }).addTo(map);
                                                    L.marker([{{ $establishment->latitude }}, {{ $establishment->longitude }}]).addTo(map)
                                                        .bindPopup("{{ $establishment->name }}").openPopup();
                                                });
                                            </script>
                                        @endif
                                        <div class="mt-2 text-sm text-light-text-muted">
                                            <strong>Dirección:</strong> {{ $establishment->address_street }}<br>
                                            <strong>Ciudad:</strong> {{ $establishment->address_city }}<br>
                                            <strong>Provincia:</strong> {{ $establishment->address_state }}<br>
                                            <strong>País:</strong> {{ $establishment->address_country }}<br>
                                            @if($establishment->latitude && $establishment->longitude)
                                                <strong>Latitud:</strong> {{ $establishment->latitude }}<br>
                                                <strong>Longitud:</strong> {{ $establishment->longitude }}<br>
                                            @endif
                                        </div>
                                        @if($establishment->notes)
                                            <div class="mt-2 text-xs text-light-text-muted"><strong>Notas:</strong> {{ $establishment->notes }}</div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col items-end ml-4">
                                        @php
                                            $contacto = $establishment->contacts->first();
                                        @endphp
                                        @if($contacto)
                                            <div class="bg-gray-900/60 rounded-lg p-3 mb-2 w-56">
                                                <p class="font-semibold text-light-text">Contacto principal</p>
                                                <p class="text-sm text-light-text-muted">{{ $contacto->name }}</p>
                                                @if($contacto->email)
                                                    <p class="text-xs text-light-text-muted">{{ $contacto->email }}</p>
                                                @endif
                                                @if($contacto->phone)
                                                    <p class="text-xs text-light-text-muted">{{ $contacto->phone }}</p>
                                                @endif
                                            </div>
                                        @endif
                                        <div class="flex items-center space-x-4 flex-shrink-0 mt-2">
                                            <a href="{{ route('clients.establishments.edit', [$client, $establishment]) }}" class="text-light-text-muted hover:text-aurora-cyan transition" title="Editar Establecimiento">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <a href="{{ route('clients.establishments.map', [$client, $establishment]) }}" class="text-light-text-muted hover:text-aurora-cyan transition" title="Ver en mapa">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </a>
                                            <form action="{{ route('clients.establishments.destroy', [$client, $establishment]) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-light-text-muted hover:text-aurora-red-pop transition" title="Eliminar Establecimiento">
                                                    <i class="fas fa-trash"></i>
                                                </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-light-text-muted py-8">No hay establecimientos para este cliente.</p>
                        @endforelse
                    </div>
                </x-card>

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
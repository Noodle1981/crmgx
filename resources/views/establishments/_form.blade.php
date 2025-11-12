{{-- resources/views/establishments/_form.blade.php --}}

<div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
    <!-- Nombre -->
    <div class="mb-6">
        <x-input-label for="name" value="Nombre de la Sede" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $establishment->name ?? '')" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <!-- Calle -->
    <div class="mb-6">
        <x-input-label for="address_street" value="Calle y Número" />
        <x-text-input id="address_street" name="address_street" type="text" class="mt-1 block w-full" :value="old('address_street', $establishment->address_street ?? '')" required />
        <x-input-error :messages="$errors->get('address_street')" class="mt-2" />
    </div>

    <!-- Ciudad -->
    <div class="mb-6">
        <x-input-label for="address_city" value="Ciudad" />
        <x-text-input id="address_city" name="address_city" type="text" class="mt-1 block w-full" :value="old('address_city', $establishment->address_city ?? '')" required />
        <x-input-error :messages="$errors->get('address_city')" class="mt-2" />
    </div>

    <!-- Código Postal -->
    <div class="mb-6">
        <x-input-label for="address_zip_code" value="Código Postal" />
        <x-text-input id="address_zip_code" name="address_zip_code" type="text" class="mt-1 block w-full" :value="old('address_zip_code', $establishment->address_zip_code ?? '')" required />
        <x-input-error :messages="$errors->get('address_zip_code')" class="mt-2" />
    </div>

    <!-- Provincia -->
    <div class="mb-6">
        <x-input-label for="address_state" value="Provincia" />
        <x-text-input id="address_state" name="address_state" type="text" class="mt-1 block w-full" :value="old('address_state', $establishment->address_state ?? '')" required />
        <x-input-error :messages="$errors->get('address_state')" class="mt-2" />
    </div>

    <!-- País -->
    <div class="mb-6">
        <x-input-label for="address_country" value="País" />
        <x-text-input id="address_country" name="address_country" type="text" class="mt-1 block w-full" :value="old('address_country', $establishment->address_country ?? 'Argentina')" required />
        <x-input-error :messages="$errors->get('address_country')" class="mt-2" />
    </div>

    <!-- Ubicación GPS -->
    <div class="mb-6">
        <x-input-label for="latitude" value="Latitud (GPS)" />
        <x-text-input id="latitude" name="latitude" type="number" step="0.00000001" class="mt-1 block w-full" :value="old('latitude', $establishment->latitude ?? '')" placeholder="Ej: -31.53750000" />
        <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
    </div>
    <div class="mb-6">
        <x-input-label for="longitude" value="Longitud (GPS)" />
        <x-text-input id="longitude" name="longitude" type="number" step="0.00000001" class="mt-1 block w-full" :value="old('longitude', $establishment->longitude ?? '')" placeholder="Ej: -68.53611111" />
        <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
    </div>
</div>

<!-- Mapa interactivo para ubicación GPS -->
<div class="mb-6">
    <x-input-label value="Ubicación en el mapa (click para seleccionar)" />
    <div id="map" style="height: 300px; border-radius: 8px; overflow: hidden;"></div>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var latInput = document.getElementById('latitude');
            var lngInput = document.getElementById('longitude');
            var lat = parseFloat(latInput.value) || -31.5375;
            var lng = parseFloat(lngInput.value) || -68.53611111;
            var map = L.map('map').setView([lat, lng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);
            var marker = L.marker([lat, lng], {draggable: true}).addTo(map);
            function updateInputs(e) {
                var pos = e.latlng || marker.getLatLng();
                latInput.value = pos.lat.toFixed(8);
                lngInput.value = pos.lng.toFixed(8);
                marker.setLatLng(pos);
            }
            map.on('click', updateInputs);
            marker.on('dragend', updateInputs);
        });
    </script>
</div>

<!-- Notas -->
<div class="mb-6">
    <x-input-label for="notes" value="Notas" />
    <textarea name="notes" id="notes" rows="4" class="mt-1 block w-full bg-gray-900/60 border border-white/10 rounded-lg text-light-text placeholder:text-light-text-muted/50 transition-all duration-300 focus:border-aurora-cyan focus:ring-2 focus:ring-aurora-cyan/40 focus:outline-none" placeholder="Añade cualquier detalle relevante aquí...">{{ old('notes', $establishment->notes ?? '') }}</textarea>
</div>

<!-- Contactos -->
<div class="mb-6">
    <x-input-label value="Contactos Asociados" />
    @if($contacts->isEmpty())
        <p class="text-light-text-muted mt-2">Este cliente no tiene contactos registrados.</p>
    @else
        <div class="mt-2 space-y-2">
            @foreach($contacts as $contact)
            <label class="flex items-center space-x-3">
                <input type="checkbox" 
                       name="contacts[]" 
                       value="{{ $contact->id }}"
                       class="rounded bg-gray-900/60 border-white/10 text-aurora-cyan focus:ring-aurora-cyan/40"
                       {{ in_array($contact->id, old('contacts', $establishment->contacts->pluck('id')->toArray() ?? [])) ? 'checked' : '' }}>
                <span class="text-light-text">
                    {{ $contact->name }} 
                    @if($contact->position)
                        <span class="text-light-text-muted">- {{ $contact->position }}</span>
                    @endif
                </span>
            </label>
            @endforeach
        </div>
    @endif
</div>

<!-- Campo Oculto para client_id -->
<input type="hidden" name="client_id" value="{{ $client->id }}">

<!-- Botones de Acción -->
<div class="flex items-center justify-end mt-8 space-x-4">
    <a href="{{ route('clients.show', $client) }}">
        <x-secondary-button type="button">Cancelar</x-secondary-button>
    </a>
    <x-primary-button>{{ $btnText }}</x-primary-button>
</div>

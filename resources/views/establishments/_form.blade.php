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
</div>

<!-- Notas -->
<div class="mb-6">
    <x-input-label for="notes" value="Notas" />
    <textarea name="notes" id="notes" rows="4" class="mt-1 block w-full bg-gray-900/60 border border-white/10 rounded-lg text-light-text placeholder:text-light-text-muted/50 transition-all duration-300 focus:border-aurora-cyan focus:ring-2 focus:ring-aurora-cyan/40 focus:outline-none" placeholder="Añade cualquier detalle relevante aquí...">{{ old('notes', $establishment->notes ?? '') }}</textarea>
</div>

<!-- Botones de Acción -->
<div class="flex items-center justify-end mt-8 space-x-4">
    <a href="{{ route('clients.show', $client) }}">
        <x-secondary-button type="button">Cancelar</x-secondary-button>
    </a>
    <x-primary-button>{{ $btnText }}</x-primary-button>
</div>

{{-- resources/views/leads/_form.blade.php --}}

<div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
    <!-- Nombre -->
    <div class="mb-6">
        <x-input-label for="name" value="Nombre" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $lead->name ?? '')" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <!-- Compañía -->
    <div class="mb-6">
        <x-input-label for="company" value="Compañía" />
        <x-text-input id="company" name="company" type="text" class="mt-1 block w-full" :value="old('company', $lead->company ?? '')" />
        <x-input-error :messages="$errors->get('company')" class="mt-2" />
    </div>

    <!-- Email -->
    <div class="mb-6">
        <x-input-label for="email" value="Email" />
        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $lead->email ?? '')" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <!-- Teléfono -->
    <div class="mb-6">
        <x-input-label for="phone" value="Teléfono" />
        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $lead->phone ?? '')" />
        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
    </div>
</div>

<!-- Fuente y Estado (en la misma fila) -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
    <!-- Fuente (Source) -->
    <div class="mb-6">
        <x-input-label for="source" value="Fuente" />
        <x-text-input id="source" name="source" type="text" class="mt-1 block w-full" :value="old('source', $lead->source ?? '')" placeholder="Ej: Conferencia, Referido, Web" />
        <x-input-error :messages="$errors->get('source')" class="mt-2" />
    </div>

    <!-- Estado (Solo en Edición) -->
    @if(isset($lead) && $lead->exists)
        <div class="mb-6">
            <x-input-label for="status" value="Estado" />
            <select name="status" id="status" class="mt-1 block w-full bg-gray-900/60 border border-white/10 rounded-lg text-light-text transition-all duration-300 focus:border-aurora-cyan focus:ring-2 focus:ring-aurora-cyan/40 focus:outline-none">
                <option value="nuevo" @selected($lead->status == 'nuevo')>Nuevo</option>
                <option value="contactado" @selected($lead->status == 'contactado')>Contactado</option>
                <option value="calificado" @selected($lead->status == 'calificado')>Calificado</option>
                <option value="perdido" @selected($lead->status == 'perdido')>Perdido</option>
            </select>
        </div>
    @endif
</div>

<!-- Notas -->
<div class="mb-6">
    <x-input-label for="notes" value="Notas" />
    <textarea name="notes" id="notes" rows="4" class="mt-1 block w-full bg-gray-900/60 border border-white/10 rounded-lg text-light-text placeholder:text-light-text-muted/50 transition-all duration-300 focus:border-aurora-cyan focus:ring-2 focus:ring-aurora-cyan/40 focus:outline-none" placeholder="Añade cualquier detalle relevante aquí...">{{ old('notes', $lead->notes ?? '') }}</textarea>
</div>

<!-- Botones de Acción -->
<div class="flex items-center justify-end mt-8 space-x-4">
    <a href="{{ route('leads.index') }}">
        <x-secondary-button type="button">Cancelar</x-secondary-button>
    </a>
    <x-primary-button>{{ $btnText }}</x-primary-button>
</div>
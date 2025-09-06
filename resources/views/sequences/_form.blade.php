{{-- resources/views/sequences/_form.blade.php --}}


<!-- Nombre de la Secuencia -->
<div class="mb-6">
    <x-input-label for="name" value="Nombre de la Secuencia" />
    {{-- AÑADIMOS x-ref AQUÍ --}}
    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $sequence->name ?? '')" required autofocus placeholder="Ej: Secuencia de Bienvenida a Nuevos Clientes" x-ref="sequenceNameInput" />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>


<!-- Botones de Acción -->
<div class="flex items-center justify-end mt-8 space-x-4">
    <a href="{{ route('sequences.index') }}">
        <x-secondary-button type="button">Cancelar</x-secondary-button>
    </a>
    <x-primary-button>{{ $btnText }}</x-primary-button>
</div>
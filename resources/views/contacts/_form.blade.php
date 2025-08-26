{{-- resources/views/contacts/_form.blade.php --}}

{{-- Campo Nombre --}}
<div class="mb-4">
    <x-input-label for="name" :value="__('Nombre')" />
    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $contact->name ?? '')" required autofocus />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

{{-- Campo Email --}}
<div class="mb-4">
    <x-input-label for="email" :value="__('Email')" />
    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $contact->email ?? '')" />
    <x-input-error :messages="$errors->get('email')" class="mt-2" />
</div>

{{-- Campo Teléfono --}}
<div class="mb-4">
    <x-input-label for="phone" :value="__('Teléfono')" />
    <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $contact->phone ?? '')" />
    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
</div>

{{-- Campo Cargo --}}
<div class="mb-4">
    <x-input-label for="position" :value="__('Cargo')" />
    <x-text-input id="position" class="block mt-1 w-full" type="text" name="position" :value="old('position', $contact->position ?? '')" />
    <x-input-error :messages="$errors->get('position')" class="mt-2" />
</div>

{{-- Botones de Acción --}}
<div class="flex items-center justify-end mt-6 space-x-4">
    <a href="{{ route('clients.show', $client) }}">
        <x-secondary-button type="button">
            {{ __('Cancelar') }}
        </x-secondary-button>
    </a>
    
    <x-primary-button>
        {{ $btnText }}
    </x-primary-button>
</div>
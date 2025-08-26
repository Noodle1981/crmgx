<x-app-layout>
    {{-- El header ahora es más simple. El layout se encarga del estilo. --}}
    <x-slot name="header">
        Añadir Contacto a {{ $client->name }}
    </x-slot>

    {{-- Reemplazamos toda la estructura de divs por nuestro componente <x-card> --}}
    <x-card class="max-w-4xl mx-auto">
        {{-- El padding ya está incluido en el componente <x-card>, así que no necesitamos divs adicionales --}}
        <form action="{{ route('clients.contacts.store', $client) }}" method="POST">
            @csrf
            
            {{-- Incluimos el formulario que ya hemos estilizado --}}
            @include('contacts._form', ['btnText' => 'Guardar Contacto'])
        </form>
    </x-card>

</x-app-layout>
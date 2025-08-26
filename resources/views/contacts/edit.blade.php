<x-app-layout>
    {{-- Simplificamos el header, dejando que el layout principal aplique el estilo --}}
    <x-slot name="header">
        Editar Contacto de {{ $client->name }}
    </x-slot>

    {{-- Usamos nuestro componente <x-card> para envolver el formulario --}}
    <x-card class="max-w-4xl mx-auto">
        <form action="{{ route('clients.contacts.update', [$client, $contact]) }}" method="POST">
            @csrf
            @method('PUT')
            
            {{-- Reutilizamos el mismo formulario parcial. ¡Funciona a la perfección! --}}
            @include('contacts._form', ['btnText' => 'Actualizar Contacto'])
        </form>
    </x-card>

</x-app-layout>
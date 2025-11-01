<x-app-layout>
    <x-slot name="header">
        Editar Sede de {{ $client->name }}
    </x-slot>
    
    <x-card class="max-w-4xl mx-auto">
        <form action="{{ route('clients.establishments.update', [$client, $establishment]) }}" method="POST">
            @csrf
            @method('PUT')
            @include('establishments._form', [
                'btnText' => 'Actualizar Sede'
            ])
        </form>
    </x-card>
</x-app-layout>

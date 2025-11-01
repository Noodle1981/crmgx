<x-app-layout>
    <x-slot name="header">
        Añadir Nueva Sede para {{ $client->name }}
    </x-slot>
    
    <x-card class="max-w-4xl mx-auto">
        <form action="{{ route('clients.establishments.store', $client) }}" method="POST">
            @csrf
            @include('establishments._form', [
                'establishment' => new \App\Models\Establishment,
                'btnText' => 'Guardar Sede'
            ])
        </form>
    </x-card>
</x-app-layout>

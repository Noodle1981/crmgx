<x-app-layout>
    <x-slot name="header">
        Crear Nuevo Cliente
    </x-slot>
    
    <x-card class="max-w-4xl mx-auto">
        <form action="{{ route('clients.store') }}" method="POST">
            @csrf
            @include('clients._form', [
                'client' => new \App\Models\Client,
                'btnText' => 'Guardar Cliente'
            ])
        </form>
    </x-card>
</x-app-layout>
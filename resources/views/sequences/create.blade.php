<x-app-layout>
    <x-slot name="header">
        Crear Nueva Secuencia
    </x-slot>
    
    <x-card class="max-w-4xl mx-auto">
        <form action="{{ route('sequences.store') }}" method="POST">
            @csrf
            @include('sequences._form', [
                'sequence' => new \App\Models\Sequence,
                'btnText' => 'Guardar y Añadir Pasos'
            ])
        </form>
    </x-card>
</x-app-layout>
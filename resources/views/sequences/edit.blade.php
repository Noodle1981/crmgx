<x-app-layout>
    <x-slot name="header">
        Editar Secuencia: {{ $sequence->name }}
    </x-slot>
    
    <x-card class="max-w-4xl mx-auto">
        <form action="{{ route('sequences.update', $sequence) }}" method="POST">
            @csrf
            @method('PUT')
            @include('sequences._form', ['btnText' => 'Actualizar Secuencia'])
        </form>
    </x-card>
</x-app-layout>
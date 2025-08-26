<x-app-layout>
    <x-slot name="header">
        Editar Paso de: {{ $sequence->name }}
    </x-slot>
    
    <x-card class="max-w-4xl mx-auto">
        <form action="{{ route('sequences.steps.update', [$sequence, $step]) }}" method="POST">
            @csrf
            @method('PUT')
            @include('sequences.steps._form', ['btnText' => 'Actualizar Paso'])
        </form>
    </x-card>
</x-app-layout>
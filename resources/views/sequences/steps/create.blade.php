<x-app-layout>
    <x-slot name="header">
        Añadir Paso a: {{ $sequence->name }}
    </x-slot>
    
    <x-card class="max-w-4xl mx-auto">
        <form action="{{ route('sequences.steps.store', $sequence) }}" method="POST">
            @csrf
            @include('sequences.steps._form', [
                'step' => new \App\Models\SequenceStep,
                'btnText' => 'Añadir Paso'
            ])
        </form>
    </x-card>
</x-app-layout>
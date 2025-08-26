<x-app-layout>
    <x-slot name="header">
        Editar Deal: {{ $deal->name }}
    </x-slot>
    <x-card class="max-w-4xl mx-auto">
        <form action="{{ route('deals.update', $deal) }}" method="POST">
            @csrf
            @method('PUT')
            @include('deals._form', ['btnText' => 'Actualizar Deal'])
        </form>
    </x-card>
</x-app-layout>
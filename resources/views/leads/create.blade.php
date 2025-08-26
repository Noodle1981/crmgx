<x-app-layout>
    <x-slot name="header">Crear Nuevo Lead</x-slot>

    <x-card class="max-w-4xl mx-auto">
        <form method="POST" action="{{ route('leads.store') }}">
            @csrf
            @include('leads._form', [
                'lead' => new \App\Models\Lead,
                'btnText' => 'Crear Lead'
            ])
        </form>
    </x-card>
</x-app-layout>
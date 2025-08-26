<x-app-layout>
    <x-slot name="header">
        @if ($selectedClient)
            Añadir Nuevo Deal a {{ $selectedClient->name }}
        @else
            Crear Nuevo Deal
        @endif
    </x-slot>
    <x-card class="max-w-4xl mx-auto">
        <form action="{{ $selectedClient ? route('clients.deals.store', $selectedClient) : route('deals.store') }}" method="POST">
            @csrf
            @if ($selectedClient)
                <input type="hidden" name="from_client_show" value="1">
            @endif
            @include('deals._form', ['btnText' => 'Guardar Deal'])
        </form>
    </x-card>
</x-app-layout>
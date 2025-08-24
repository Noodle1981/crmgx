<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{-- El título cambia dinámicamente si venimos de un cliente --}}
            @if ($selectedClient)
                Añadir Nuevo Deal a {{ $selectedClient->name }}
            @else
                Crear Nuevo Deal
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- ESTE ES EL FORMULARIO COMPLETO Y CORRECTO --}}
                    <form action="{{ $selectedClient ? route('clients.deals.store', $selectedClient) : route('deals.store') }}" method="POST">
                        @csrf
                        
                        {{-- Campo oculto para saber a dónde redirigir después de guardar --}}
                        @if ($selectedClient)
                            <input type="hidden" name="from_client_show" value="1">
                        @endif
                        
                        {{-- Incluimos el formulario parcial --}}
                        @include('deals._form', ['btnText' => 'Guardar Deal'])
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
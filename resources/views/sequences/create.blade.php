<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold...">Crear Nueva Secuencia</h2></x-slot>
    <div class="py-12"><div class="..."><div class="..."><div class="p-6 ...">
        <form action="{{ route('sequences.store') }}" method="POST">
            @csrf
            @include('sequences._form', ['btnText' => 'Guardar Secuencia'])
        </form>
    </div></div></div></div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold...">Editar Secuencia</h2></x-slot>
    <div class="py-12"><div class="..."><div class="..."><div class="p-6 ...">
        <form action="{{ route('sequences.update', $sequence) }}" method="POST">
            @csrf
            @method('PUT')
            @include('sequences._form', ['btnText' => 'Actualizar Secuencia'])
        </form>
    </div></div></div></div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('messages.sales_pipeline') }}
            </h2>
            <a href="{{ route('deals.create') }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded-md">
                Crear Nuevo Deal
            </a>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach ($pipelineData as $stage)
                        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="font-bold text-lg mb-4">{{ $stage['name'] }}</h3>
                            
                            @forelse ($stage['deals'] as $deal)
                                <div class="bg-white dark:bg-gray-800 p-3 rounded-md shadow mb-3">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-semibold">{{ $deal->name }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $deal->client->name }}</p>
                                            @if($deal->value)
                                            <p class="text-sm font-bold mt-2">${{ number_format($deal->value, 2) }}</p>
                                            @endif
                                        </div>
                                        <!-- ¡AQUÍ ESTÁN LOS ENLACES CRUD! -->
                                        <div class="text-xs text-gray-500 space-x-2 flex-shrink-0 ml-2">

                                            <form action="{{ route('deals.win', $deal) }}" method="POST" class="inline">
        @csrf
        @method('PATCH')
        <button type="submit" class="font-bold text-green-500 hover:underline">Ganado</button>
    </form>
    <form action="{{ route('deals.lost', $deal) }}" method="POST" class="inline">
        @csrf
        @method('PATCH')
        <button type="submit" class="font-bold text-gray-500 hover:underline">Perdido</button>
    </form>



                                            <a href="{{ route('deals.edit', $deal) }}" class="hover:underline">Editar</a>
                                            <form action="{{ route('deals.destroy', $deal) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="hover:underline text-red-500">Eliminar</button>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3 flex items-center justify-end space-x-2 text-xs">
                                        @if ($deal->deal_stage_id > 1)
                                            <form action="{{ route('deals.updateStage', $deal) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="deal_stage_id" value="{{ $deal->deal_stage_id - 1 }}">
                                                <button type="submit" class="font-semibold text-gray-500 hover:text-gray-700">←</button>
                                            </form>
                                        @endif

                                        @if ($deal->deal_stage_id < $pipelineData->count())
                                            <form action="{{ route('deals.updateStage', $deal) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="deal_stage_id" value="{{ $deal->deal_stage_id + 1 }}">
                                                <button type="submit" class="font-semibold text-blue-500 hover:text-blue-700">→</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">No deals in this stage.</p>
                            @endforelse
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
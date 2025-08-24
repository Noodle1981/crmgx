<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Leads') }}</h2>
            <a href="{{ route('leads.create') }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded-md">Crear Nuevo Lead</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert"><p>{{ session('success') }}</p></div>
            @endif
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="min-w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Compañía</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Estado</th>
                                <th class="relative px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($leads as $lead)
                                <tr>
                                    <td class="px-6 py-4">{{ $lead->name }}</td>
                                    <td class="px-6 py-4">{{ $lead->company }}</td>
                                    <td class="px-6 py-4">{{ $lead->email }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($lead->status == 'nuevo') bg-blue-100 text-blue-800 @endif
                                            @if($lead->status == 'contactado') bg-yellow-100 text-yellow-800 @endif
                                            @if($lead->status == 'calificado') bg-green-100 text-green-800 @endif
                                            @if($lead->status == 'perdido') bg-red-100 text-red-800 @endif
                                        ">
                                            {{ ucfirst($lead->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                                        
                                        @if ($lead->status === 'calificado')
        <form action="{{ route('leads.convert', $lead) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="font-bold text-green-600 hover:text-green-900">Convertir</button>
        </form>
    @endif
                                        
                                        
                                        <a href="{{ route('leads.edit', $lead) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>
                                        <form action="{{ route('leads.destroy', $lead) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-4">No se encontraron leads activos.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $leads->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text-main leading-tight">
            Sedes
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($establishments->isEmpty())
                <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-100">
                        No hay sedes para mostrar.
                    </div>
                </div>
            @else
                <div class="space-y-8">
                    @foreach($establishments as $clientId => $clientEstablishments)
                        <x-card class="!p-0">
                            <h3 class="text-2xl font-bold mb-4 text-white p-6">{{ $clientEstablishments->first()->client->name }}</h3>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="border-b border-white/10">
                                        <tr>
                                            <th class="p-4 text-left font-semibold text-light-text-muted uppercase tracking-wider">Sede</th>
                                            <th class="p-4 text-left font-semibold text-light-text-muted uppercase tracking-wider">Dirección</th>
                                            <th class="p-4 text-left font-semibold text-light-text-muted uppercase tracking-wider">Contactos</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-white/5">
                                        @foreach($clientEstablishments as $establishment)
                                            <tr class="hover:bg-gray-800/50 transition-colors duration-200">
                                                <td class="p-4 font-medium text-light-text">{{ $establishment->name }}</td>
                                                <td class="p-4 text-light-text-muted">{{ $establishment->address_street }}, {{ $establishment->address_city }}, {{ $establishment->address_state }}, {{ $establishment->address_country }}</td>
                                                <td class="p-4 text-light-text-muted">
                                                    @if($establishment->contacts->isNotEmpty())
                                                        <ul class="list-disc list-inside">
                                                            @foreach($establishment->contacts as $contact)
                                                                <li>{{ $contact->name }} - {{ $contact->email }}</li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        Sin contactos
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </x-card>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

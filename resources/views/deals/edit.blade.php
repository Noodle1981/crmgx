<x-app-layout>
    <x-slot name="header">
        Editar Deal: {{ $deal->name }}
    </x-slot>
    <x-card class="max-w-4xl mx-auto">
        <form action="{{ route('deals.update', $deal) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Nombre del Deal -->
            <div class="mb-6">
                <x-input-label for="name" value="Nombre del Deal:" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $deal->name ?? '')" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>




            <div class="space-y-4">
                {{-- Stage 1: Contacto Inicial --}}
                <div>
                    <label for="notes_contact" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                        Notas de Contacto Inicial:
                    </label>
                    @if($deal->deal_stage_id == 1)
                        <textarea name="notes_contact" id="notes_contact" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-900 leading-tight focus:outline-none focus:shadow-outline">{{ old('notes_contact', $deal->notes_contact ?? '') }}</textarea>
                    @else
                        <div class="prose prose-invert max-w-none p-4 bg-gray-800 rounded-md">{!! nl2br(e($deal->notes_contact)) !!}</div>
                    @endif
                    @error('notes_contact') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Stage 2: Propuesta Enviada --}}
                @if($deal->deal_stage_id >= 2)
                <div>
                    <label for="notes_proposal" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                        Notas de Propuesta Enviada:
                    </label>
                    @if($deal->deal_stage_id == 2)
                        <textarea name="notes_proposal" id="notes_proposal" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-900 leading-tight focus:outline-none focus:shadow-outline">{{ old('notes_proposal', $deal->notes_proposal ?? '') }}</textarea>
                    @else
                        <div class="prose prose-invert max-w-none p-4 bg-gray-800 rounded-md">{!! nl2br(e($deal->notes_proposal)) !!}</div>
                    @endif
                    @error('notes_proposal') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                @endif

                {{-- Stage 3: Negociación --}}
                @if($deal->deal_stage_id >= 3)
                <div>
                    <label for="notes_negotiation" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                        Notas de Negociación:
                    </label>
                    @if($deal->deal_stage_id == 3)
                        <textarea name="notes_negotiation" id="notes_negotiation" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-900 leading-tight focus:outline-none focus:shadow-outline">{{ old('notes_negotiation', $deal->notes_negotiation ?? '') }}</textarea>
                    @else
                        <div class="prose prose-invert max-w-none p-4 bg-gray-800 rounded-md">{!! nl2br(e($deal->notes_negotiation)) !!}</div>
                    @endif
                    @error('notes_negotiation') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                @endif
            </div>

            <!-- Valor -->
            <div class="mb-6 mt-6">
                <x-input-label for="value" value="Valor ($):" />
                <x-text-input id="value" name="value" type="number" step="0.01" class="mt-1 block w-full" :value="old('value', $deal->value ?? '')" placeholder="Ej: 1500.00" />
                <x-input-error :messages="$errors->get('value')" class="mt-2" />
            </div>

            <!-- Fecha de Cierre Prevista -->
            <div class="mb-6">
                <x-input-label for="expected_close_date" value="Fecha de Cierre Prevista:" />
                <x-text-input id="expected_close_date" name="expected_close_date" type="date" class="mt-1 block w-full" :value="old('expected_close_date', $deal->expected_close_date ? \Carbon\Carbon::parse($deal->expected_close_date)->format('Y-m-d') : '')" />
                <x-input-error :messages="$errors->get('expected_close_date')" class="mt-2" />
            </div>

            <!-- Cliente Asociado -->
            <div class="mb-6">
                <x-input-label for="client_id" value="Cliente:" />
                <select name="client_id" id="client_id" class="mt-1 block w-full bg-gray-900/60 border border-white/10 rounded-lg text-light-text transition-all duration-300 focus:border-aurora-cyan focus:ring-2 focus:ring-aurora-cyan/40 focus:outline-none" required>
                    <option value="" disabled>Selecciona un cliente...</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}" @selected(old('client_id', $deal->client_id ?? '') == $client->id)>
                            {{ $client->name }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('client_id')" class="mt-2" />
            </div>

            <!-- Botones de Acción -->
            <div class="flex items-center justify-end mt-8 space-x-4">
                @php
                    $cancelUrl = route('deals.index');
                    if (request('from_client_show') && isset($deal->client_id)) {
                        $cancelUrl = route('clients.show', $deal->client_id);
                    }
                @endphp
                <a href="{{ $cancelUrl }}">
                    <x-secondary-button type="button">Cancelar</x-secondary-button>
                </a>
                <x-primary-button>Actualizar Deal</x-primary-button>
            </div>
        </form>
    </x-card>
</x-app-layout>

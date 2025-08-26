<!-- Nombre del Deal -->
<div class="mb-6">
    <x-input-label for="name" value="Nombre del Deal:" />
    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $deal->name ?? '')" required autofocus />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<!-- Valor -->
<div class="mb-6">
    <x-input-label for="value" value="Valor ($):" />
    <x-text-input id="value" name="value" type="number" step="0.01" class="mt-1 block w-full" :value="old('value', $deal->value ?? '')" placeholder="Ej: 1500.00" />
    <x-input-error :messages="$errors->get('value')" class="mt-2" />
</div>

<!-- Cliente Asociado -->
<div class="mb-6">
    <x-input-label for="client_id" value="Cliente:" />
    <select name="client_id" id="client_id" class="mt-1 block w-full bg-gray-900/60 border border-white/10 rounded-lg text-light-text transition-all duration-300 focus:border-aurora-cyan focus:ring-2 focus:ring-aurora-cyan/40 focus:outline-none" required>
        <option value="" disabled selected>Selecciona un cliente...</option>
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
    <x-primary-button>{{ $btnText }}</x-primary-button>
</div>
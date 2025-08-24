<!-- Nombre del Deal -->
<div class="mb-4">
    <label for="name" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Nombre del Deal:</label>
    <input type="text" name="name" id="name" value="{{ old('name', $deal->name ?? '') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-900 leading-tight focus:outline-none focus:shadow-outline" required>
    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>

<!-- Valor -->
<div class="mb-4">
    <label for="value" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Valor ($):</label>
    <input type="number" step="0.01" name="value" id="value" value="{{ old('value', $deal->value ?? '') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-900 leading-tight focus:outline-none focus:shadow-outline">
    @error('value') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>

<!-- Cliente Asociado -->
<div class="mb-4">
    <label for="client_id" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Cliente:</label>
    <select name="client_id" id="client_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-900 leading-tight focus:outline-none focus:shadow-outline" required @if($selectedClient) disabled @endif>
        @foreach ($clients as $client)
            <option value="{{ $client->id }}" {{ ($selectedClient && $selectedClient->id == $client->id) ? 'selected' : ( (old('client_id') ?? ($deal->client_id ?? '')) == $client->id ? 'selected' : '' ) }}>
                {{ $client->name }}
            </option>
        @endforeach
    </select>
    @if($selectedClient)
        <input type="hidden" name="client_id" value="{{ $selectedClient->id }}">
    @endif
    @error('client_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>

<div class="flex items-center justify-end mt-4">
    <a href="{{ $selectedClient ? route('clients.show', $selectedClient) : route('deals.index') }}" class="text-gray-600 dark:text-gray-400 hover:underline mr-4">Cancelar</a>
    <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded-md">
        {{ $btnText }}
    </button>
</div>
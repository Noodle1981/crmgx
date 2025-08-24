<div class="mb-4">
    <label for="name" class="block text-sm font-bold mb-2 dark:text-gray-300">Nombre</label>
    <input type="text" name="name" id="name" value="{{ old('name', $contact->name ?? '') }}" class="shadow rounded w-full py-2 px-3 dark:bg-gray-900 dark:text-gray-300" required>
</div>
<div class="mb-4">
    <label for="email" class="block text-sm font-bold mb-2 dark:text-gray-300">Email</label>
    <input type="email" name="email" id="email" value="{{ old('email', $contact->email ?? '') }}" class="shadow rounded w-full py-2 px-3 dark:bg-gray-900 dark:text-gray-300">
</div>
<div class="mb-4">
    <label for="phone" class="block text-sm font-bold mb-2 dark:text-gray-300">Teléfono</label>
    <input type="text" name="phone" id="phone" value="{{ old('phone', $contact->phone ?? '') }}" class="shadow rounded w-full py-2 px-3 dark:bg-gray-900 dark:text-gray-300">
</div>
<div class="mb-4">
    <label for="position" class="block text-sm font-bold mb-2 dark:text-gray-300">Cargo</label>
    <input type="text" name="position" id="position" value="{{ old('position', $contact->position ?? '') }}" class="shadow rounded w-full py-2 px-3 dark:bg-gray-900 dark:text-gray-300">
</div>
<div class="flex items-center justify-end mt-4">
    <a href="{{ route('clients.show', $client) }}" class="dark:text-gray-400 hover:underline mr-4">Cancelar</a>
    <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded-md">{{ $btnText }}</button>
</div>
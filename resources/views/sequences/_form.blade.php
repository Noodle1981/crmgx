<div class="mb-4">
    <label for="name" class="block text-sm font-bold mb-2 dark:text-gray-300">Nombre de la Secuencia</label>
    <input type="text" name="name" id="name" value="{{ old('name', $sequence->name ?? '') }}" class="shadow rounded w-full py-2 px-3 dark:bg-gray-900 dark:text-gray-300" required>
    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>
<div class="flex items-center justify-end mt-4">
    <a href="{{ route('sequences.index') }}" class="dark:text-gray-400 hover:underline mr-4">Cancelar</a>
    <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded-md">{{ $btnText }}</button>
</div>
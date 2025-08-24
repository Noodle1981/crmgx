<!-- Nombre -->
<div class="mb-4">
    <label for="name" class="block text-sm font-bold mb-2 dark:text-gray-300">Nombre</label>
    <input type="text" name="name" id="name" value="{{ old('name', $lead->name ?? '') }}" class="shadow rounded w-full py-2 px-3 dark:bg-gray-900 dark:text-gray-300 focus:outline-none" required>
    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>

<!-- Compañía -->
<div class="mb-4">
    <label for="company" class="block text-sm font-bold mb-2 dark:text-gray-300">Compañía</label>
    <input type="text" name="company" id="company" value="{{ old('company', $lead->company ?? '') }}" class="shadow rounded w-full py-2 px-3 dark:bg-gray-900 dark:text-gray-300 focus:outline-none">
    @error('company') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>

<!-- Email -->
<div class="mb-4">
    <label for="email" class="block text-sm font-bold mb-2 dark:text-gray-300">Email</label>
    <input type="email" name="email" id="email" value="{{ old('email', $lead->email ?? '') }}" class="shadow rounded w-full py-2 px-3 dark:bg-gray-900 dark:text-gray-300 focus:outline-none">
    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>

<!-- Teléfono -->
<div class="mb-4">
    <label for="phone" class="block text-sm font-bold mb-2 dark:text-gray-300">Teléfono</label>
    <input type="text" name="phone" id="phone" value="{{ old('phone', $lead->phone ?? '') }}" class="shadow rounded w-full py-2 px-3 dark:bg-gray-900 dark:text-gray-300 focus:outline-none">
    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p @enderror
</div>

<!-- Fuente (Source) -->
<div class="mb-4">
    <label for="source" class="block text-sm font-bold mb-2 dark:text-gray-300">Fuente</label>
    <input type="text" name="source" id="source" value="{{ old('source', $lead->source ?? '') }}" class="shadow rounded w-full py-2 px-3 dark:bg-gray-900 dark:text-gray-300 focus:outline-none">
    @error('source') <p class="text-red-500 text-xs mt-1">{{ $message }}</p @enderror
</div>

<!-- Estado (Solo en Edición) -->
@isset($lead)
<div class="mb-4">
    <label for="status" class="block text-sm font-bold mb-2 dark:text-gray-300">Estado</label>
    <select name="status" id="status" class="shadow rounded w-full py-2 px-3 dark:bg-gray-900 dark:text-gray-300 focus:outline-none">
        <option value="nuevo" {{ $lead->status == 'nuevo' ? 'selected' : '' }}>Nuevo</option>
        <option value="contactado" {{ $lead->status == 'contactado' ? 'selected' : '' }}>Contactado</option>
        <option value="calificado" {{ $lead->status == 'calificado' ? 'selected' : '' }}>Calificado</option>
        <option value="perdido" {{ $lead->status == 'perdido' ? 'selected' : '' }}>Perdido</option>
    </select>
</div>
@endisset

<!-- Notas -->
<div class="mb-4">
    <label for="notes" class="block text-sm font-bold mb-2 dark:text-gray-300">Notas</label>
    <textarea name="notes" id="notes" rows="4" class="shadow rounded w-full py-2 px-3 dark:bg-gray-900 dark:text-gray-300 focus:outline-none">{{ old('notes', $lead->notes ?? '') }}</textarea>
</div>

<div class="flex items-center justify-end mt-4">
    <a href="{{ route('leads.index') }}" class="dark:text-gray-400 hover:underline mr-4">Cancelar</a>
    <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded-md">{{ $btnText }}</button>
</div>
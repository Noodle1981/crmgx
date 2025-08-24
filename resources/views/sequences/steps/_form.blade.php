<div class="mb-4">
    <label for="type" class="block text-sm font-bold mb-2 dark:text-gray-300">Tipo de Paso</label>
    <select name="type" id="type" class="shadow rounded w-full py-2 px-3 dark:bg-gray-900 dark:text-gray-300" required>
        <option value="task" {{ (old('type', $step->type ?? '') == 'task') ? 'selected' : '' }}>Crear Tarea</option>
        <option value="email" {{ (old('type', $step->type ?? '') == 'email') ? 'selected' : '' }}>Enviar Email</option>
    </select>
</div>
<div class="mb-4">
    <label for="delay_days" class="block text-sm font-bold mb-2 dark:text-gray-300">Ejecutar después de (días)</label>
    <input type="number" name="delay_days" id="delay_days" value="{{ old('delay_days', $step->delay_days ?? 1) }}" class="shadow rounded w-full py-2 px-3 dark:bg-gray-900 dark:text-gray-300" required min="0">
    <p class="text-xs text-gray-500 mt-1">Usa 0 para ejecutar el mismo día de la inscripción.</p>
</div>
<div class="mb-4">
    <label for="subject" class="block text-sm font-bold mb-2 dark:text-gray-300">Asunto (para emails)</label>
    <input type="text" name="subject" id="subject" value="{{ old('subject', $step->subject ?? '') }}" class="shadow rounded w-full py-2 px-3 dark:bg-gray-900 dark:text-gray-300">
</div>
<div class="mb-4">
    <label for="body" class="block text-sm font-bold mb-2 dark:text-gray-300">Cuerpo del Email / Título de la Tarea</label>
    <textarea name="body" id="body" rows="5" class="shadow rounded w-full py-2 px-3 dark:bg-gray-900 dark:text-gray-300" required>{{ old('body', $step->body ?? '') }}</textarea>
</div>
<div class="flex items-center justify-end mt-4">
    <a href="{{ route('sequences.show', $sequence) }}" class="dark:text-gray-400 hover:underline mr-4">Cancelar</a>
    <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded-md">{{ $btnText }}</button>
</div>
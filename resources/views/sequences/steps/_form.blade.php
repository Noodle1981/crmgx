{{-- Usamos Alpine.js para controlar la visibilidad de los campos --}}
<div x-data="{ type: '{{ old('type', $step->type ?? 'task') }}' }">

    {{-- Layout de dos columnas para los campos principales --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
        <!-- Tipo de Paso -->
        <div class="mb-6">
            <x-input-label for="type" value="Tipo de Paso" />
            <select name="type" id="type" x-model="type" class="mt-1 block w-full bg-gray-900/60 border border-white/10 rounded-lg text-light-text transition-all duration-300 focus:border-aurora-cyan focus:ring-2 focus:ring-aurora-cyan/40" required>
                <option value="task">Crear Tarea</option>
                <option value="email">Enviar Email</option>
            </select>
        </div>

        <!-- Delay (días) -->
        <div class="mb-6">
            <x-input-label for="delay_days" value="Ejecutar después de (días)" />
            <x-text-input type="number" name="delay_days" id="delay_days" :value="old('delay_days', $step->delay_days ?? 1)" class="mt-1 block w-full" required min="0" />
            <p class="text-sm text-light-text-muted mt-2">Usa 0 para ejecutar el mismo día de la inscripción.</p>
        </div>
    </div>

    <!-- Asunto (SOLO APARECE SI SE SELECCIONA "EMAIL") -->
    <div x-show="type === 'email'" x-transition class="mb-6">
        <x-input-label for="subject" value="Asunto (para emails)" />
        <x-text-input type="text" name="subject" id="subject" :value="old('subject', $step->subject ?? '')" class="mt-1 block w-full" />
        <x-input-error :messages="$errors->get('subject')" class="mt-2" />
    </div>

    <!-- Cuerpo del Email / Título de la Tarea -->
    <div class="mb-6">
        {{-- La etiqueta cambia dinámicamente según la selección --}}
        <label for="body" class="block font-medium text-sm text-light-text-muted">
            <span x-show="type === 'email'">Cuerpo del Email</span>
            <span x-show="type === 'task'">Título y Descripción de la Tarea</span>
        </label>
        <textarea name="body" id="body" rows="5" class="mt-1 block w-full bg-gray-900/60 border border-white/10 rounded-lg text-light-text placeholder:text-light-text-muted/50 transition-all duration-300 focus:border-aurora-cyan focus:ring-2 focus:ring-aurora-cyan/40" required>{{ old('body', $step->body ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('body')" class="mt-2" />
    </div>

    <!-- Botones de Acción -->
    <div class="flex items-center justify-end mt-8 space-x-4">
        <a href="{{ route('sequences.show', $sequence) }}">
            <x-secondary-button type="button">Cancelar</x-secondary-button>
        </a>
        <x-primary-button>{{ $btnText }}</x-primary-button>
    </div>
</div>
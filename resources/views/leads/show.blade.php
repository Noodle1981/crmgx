<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $lead->name }}
                <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">
                    {{ $lead->company ? '(' . $lead->company . ')' : '' }}
                </span>
            </h2>
            <div class="flex items-center space-x-4">
                @if($lead->status === 'calificado')
                    <form action="{{ route('leads.convert', $lead) }}" method="POST" class="inline">
                        @csrf
                        <x-primary-button type="submit">
                            <i class="fas fa-exchange-alt mr-2"></i>
                            Convertir a Cliente
                        </x-primary-button>
                    </form>
                @endif
                <a href="{{ route('leads.edit', $lead) }}" class="inline-flex items-center">
                    <x-secondary-button>
                        <i class="fas fa-edit mr-2"></i>
                        Editar
                    </x-secondary-button>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Columna de Información Principal -->
                <div class="md:col-span-2 space-y-6">
                    <!-- Información Básica -->
                    <x-card>
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-semibold mb-4">Información de Contacto</h3>
                                <dl class="space-y-2">
                                    @if($lead->email)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                            <dd class="mt-1">
                                                <a href="mailto:{{ $lead->email }}" class="text-aurora-cyan hover:underline">
                                                    {{ $lead->email }}
                                                </a>
                                            </dd>
                                        </div>
                                    @endif
                                    
                                    @if($lead->phone)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Teléfono</dt>
                                            <dd class="mt-1">
                                                <div class="flex items-center space-x-2">
                                                    <span>{{ $lead->phone }}</span>
                                                    @php
                                                        $phoneDigits = preg_replace('/\D+/', '', $lead->phone);
                                                        $waMessage = rawurlencode("Hola {$lead->name}");
                                                        $waUrl = "https://wa.me/{$phoneDigits}?text={$waMessage}";
                                                    @endphp
                                                    <a href="{{ $waUrl }}" 
                                                       target="_blank"
                                                       class="text-green-500 hover:text-green-600">
                                                        <i class="fab fa-whatsapp"></i>
                                                    </a>
                                                </div>
                                            </dd>
                                        </div>
                                    @endif
                                </dl>
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-semibold mb-4">Detalles</h3>
                                <dl class="space-y-2">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Fuente</dt>
                                        <dd class="mt-1">{{ $lead->source ?: 'No especificada' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Estado</dt>
                                        <dd class="mt-1">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @switch($lead->status)
                                                    @case('nuevo')
                                                        bg-blue-100 text-blue-800
                                                        @break
                                                    @case('contactado')
                                                        bg-yellow-100 text-yellow-800
                                                        @break
                                                    @case('calificado')
                                                        bg-green-100 text-green-800
                                                        @break
                                                    @case('perdido')
                                                        bg-red-100 text-red-800
                                                        @break
                                                    @default
                                                        bg-gray-100 text-gray-800
                                                @endswitch">
                                                {{ ucfirst($lead->status) }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Creado</dt>
                                        <dd class="mt-1">{{ $lead->created_at->format('d/m/Y H:i') }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        @if($lead->notes)
                            <div class="mt-6">
                                <h3 class="text-lg font-semibold mb-2">Notas</h3>
                                <p class="text-gray-600 dark:text-gray-300">
                                    {{ $lead->notes }}
                                </p>
                            </div>
                        @endif
                    </x-card>

                    <!-- Actividades -->
                    <x-card>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Actividades</h3>
                            <button type="button" 
                                    onclick="Livewire.emit('openModal', 'create-activity-modal', {{ json_encode(['loggableId' => $lead->id, 'loggableType' => 'App\\Models\\Lead']) }})"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-aurora-cyan hover:bg-aurora-cyan/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-aurora-cyan/40">
                                <i class="fas fa-plus mr-2"></i>
                                Nueva Actividad
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            @forelse($lead->activities()->latest()->get() as $activity)
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full
                                            @switch($activity->type)
                                                @case('call')
                                                    bg-yellow-100
                                                    @break
                                                @case('meeting')
                                                    bg-purple-100
                                                    @break
                                                @case('email')
                                                    bg-blue-100
                                                    @break
                                                @default
                                                    bg-gray-100
                                            @endswitch">
                                            <i class="fas
                                                @switch($activity->type)
                                                    @case('call')
                                                        fa-phone text-yellow-600
                                                        @break
                                                    @case('meeting')
                                                        fa-users text-purple-600
                                                        @break
                                                    @case('email')
                                                        fa-envelope text-blue-600
                                                        @break
                                                    @default
                                                        fa-sticky-note text-gray-600
                                                @endswitch">
                                            </i>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ ucfirst($activity->type) }}
                                        </div>
                                        <div class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                            {{ $activity->description }}
                                        </div>
                                        <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                            {{ $activity->created_at->diffForHumans() }} por {{ $activity->user->name }}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400 text-center py-4">
                                    No hay actividades registradas
                                </p>
                            @endforelse
                        </div>
                    </x-card>
                </div>

                <!-- Columna de Recordatorios y Tareas -->
                <div class="space-y-6">
                    <!-- Recordatorios -->
                    <x-card>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Recordatorios</h3>
                            <button type="button" 
                                    x-data=""
                                    x-on:click="$dispatch('open-modal', 'create-reminder')"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-aurora-cyan hover:bg-aurora-cyan/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-aurora-cyan/40">
                                <i class="fas fa-bell mr-2"></i>
                                Nuevo Recordatorio
                            </button>
                        </div>

                        <x-lead-reminders :reminders="$lead->reminders" />
                    </x-card>

                    <!-- Tareas -->
                    <x-card>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Tareas</h3>
                            <button type="button" 
                                    onclick="Livewire.emit('openModal', 'create-task-modal', {{ json_encode(['taskableId' => $lead->id, 'taskableType' => 'App\\Models\\Lead']) }})"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-aurora-cyan hover:bg-aurora-cyan/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-aurora-cyan/40">
                                <i class="fas fa-tasks mr-2"></i>
                                Nueva Tarea
                            </button>
                        </div>

                        <div class="space-y-4">
                            @forelse($lead->tasks()->orderBy('due_date')->get() as $task)
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full
                                            @if($task->completed_at)
                                                bg-green-100
                                            @elseif($task->due_date && $task->due_date->isPast())
                                                bg-red-100
                                            @else
                                                bg-blue-100
                                            @endif">
                                            <i class="fas fa-tasks
                                                @if($task->completed_at)
                                                    text-green-600
                                                @elseif($task->due_date && $task->due_date->isPast())
                                                    text-red-600
                                                @else
                                                    text-blue-600
                                                @endif">
                                            </i>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center justify-between">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $task->title }}
                                            </div>
                                            @if(!$task->completed_at)
                                                <form action="{{ route('tasks.complete', $task) }}" method="POST" class="flex-shrink-0">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="text-green-600 hover:text-green-800"
                                                            title="Marcar como completada">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                        <div class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                            {{ $task->description }}
                                        </div>
                                        <div class="mt-2 text-xs text-gray-500 dark:text-gray-400 flex items-center space-x-2">
                                            <span>
                                                <i class="far fa-calendar mr-1"></i>
                                                {{ $task->due_date ? $task->due_date->format('d/m/Y H:i') : 'Sin fecha' }}
                                            </span>
                                            @if($task->completed_at)
                                                <span class="text-green-600">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Completada {{ $task->completed_at->diffForHumans() }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400 text-center py-4">
                                    No hay tareas pendientes
                                </p>
                            @endforelse
                        </div>
                    </x-card>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Crear Recordatorio -->
    <x-modal name="create-reminder" :show="false">
        <form method="POST" action="{{ route('lead-reminders.store') }}" class="p-6">
            @csrf
            <input type="hidden" name="lead_id" value="{{ $lead->id }}">
            
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                Crear Nuevo Recordatorio
            </h2>

            <div class="space-y-4">
                <div>
                    <x-input-label for="title" value="Título" />
                    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" required />
                </div>

                <div>
                    <x-input-label for="description" value="Descripción" />
                    <textarea id="description" 
                              name="description" 
                              rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm"></textarea>
                </div>

                <div>
                    <x-input-label for="due_date" value="Fecha de Vencimiento" />
                    <x-text-input id="due_date" 
                                name="due_date" 
                                type="datetime-local" 
                                class="mt-1 block w-full" 
                                required />
                </div>

                <div>
                    <x-input-label for="priority" value="Prioridad" />
                    <select id="priority" 
                            name="priority" 
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                        <option value="low">Baja</option>
                        <option value="medium" selected>Media</option>
                        <option value="high">Alta</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <x-secondary-button type="button" x-on:click="$dispatch('close')">
                    Cancelar
                </x-secondary-button>

                <x-primary-button type="submit">
                    Crear Recordatorio
                </x-primary-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Configuración Personal
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-6">
                            <!-- Preferencias de Notificaciones -->
                            <div>
                                <h3 class="text-lg font-medium">Preferencias de Notificaciones</h3>
                                <div class="mt-4 space-y-4">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="email_notifications" name="email_notifications" type="checkbox"
                                                   class="rounded border-gray-300 text-primary focus:ring-primary"
                                                   {{ old('email_notifications', $settings['email_notifications'] ?? true) ? 'checked' : '' }}>
                                        </div>
                                        <div class="ml-3">
                                            <label for="email_notifications" class="font-medium">Notificaciones por Email</label>
                                            <p class="text-gray-500">Recibir notificaciones importantes por correo electrónico</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="task_reminders" name="task_reminders" type="checkbox"
                                                   class="rounded border-gray-300 text-primary focus:ring-primary"
                                                   {{ old('task_reminders', $settings['task_reminders'] ?? true) ? 'checked' : '' }}>
                                        </div>
                                        <div class="ml-3">
                                            <label for="task_reminders" class="font-medium">Recordatorios de Tareas</label>
                                            <p class="text-gray-500">Recibir recordatorios de tareas pendientes</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Preferencias de Visualización -->
                            <div class="pt-6">
                                <h3 class="text-lg font-medium">Preferencias de Visualización</h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label for="date_format" class="block font-medium">Formato de Fecha</label>
                                        <select id="date_format" name="date_format" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                            <option value="d/m/Y" {{ old('date_format', $settings['date_format'] ?? '') === 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                                            <option value="Y-m-d" {{ old('date_format', $settings['date_format'] ?? '') === 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                                            <option value="m/d/Y" {{ old('date_format', $settings['date_format'] ?? '') === 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="timezone" class="block font-medium">Zona Horaria</label>
                                        <select id="timezone" name="timezone" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                            <option value="America/Argentina/Buenos_Aires" {{ old('timezone', $settings['timezone'] ?? '') === 'America/Argentina/Buenos_Aires' ? 'selected' : '' }}>Argentina (Buenos Aires)</option>
                                            <option value="America/Santiago" {{ old('timezone', $settings['timezone'] ?? '') === 'America/Santiago' ? 'selected' : '' }}>Chile (Santiago)</option>
                                            <!-- Agregar más zonas horarias según sea necesario -->
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                Guardar Preferencias
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
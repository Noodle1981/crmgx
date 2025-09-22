<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Configuración de Correo Electrónico
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
                    <p class="mb-4 text-gray-600 dark:text-gray-400">
                        Aquí puedes configurar los detalles de tu servidor de correo (SMTP) para el envío de emails automáticos desde la plataforma.
                    </p>
                    <form action="{{ route('settings.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Mailer (Driver) -->
                            <div>
                                <x-input-label for="mail_mailer" value="Mailer (ej: smtp)" />
                                <x-text-input id="mail_mailer" name="mail_mailer" type="text" class="mt-1 block w-full" :value="old('mail_mailer', $settings['mail_mailer'] ?? 'smtp')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('mail_mailer')" />
                            </div>

                            <!-- Host -->
                            <div>
                                <x-input-label for="mail_host" value="Host SMTP (ej: smtp.gmail.com)" />
                                <x-text-input id="mail_host" name="mail_host" type="text" class="mt-1 block w-full" :value="old('mail_host', $settings['mail_host'] ?? '')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('mail_host')" />
                            </div>

                            <!-- Port -->
                            <div>
                                <x-input-label for="mail_port" value="Puerto (ej: 587)" />
                                <x-text-input id="mail_port" name="mail_port" type="number" class="mt-1 block w-full" :value="old('mail_port', $settings['mail_port'] ?? '587')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('mail_port')" />
                            </div>

                            <!-- Encryption -->
                            <div>
                                <x-input-label for="mail_encryption" value="Cifrado (ej: tls, ssl)" />
                                <x-text-input id="mail_encryption" name="mail_encryption" type="text" class="mt-1 block w-full" :value="old('mail_encryption', $settings['mail_encryption'] ?? 'tls')" />
                                <x-input-error class="mt-2" :messages="$errors->get('mail_encryption')" />
                            </div>

                            <!-- Username -->
                            <div class="md:col-span-2">
                                <x-input-label for="mail_username" value="Nombre de Usuario" />
                                <x-text-input id="mail_username" name="mail_username" type="text" class="mt-1 block w-full" :value="old('mail_username', $settings['mail_username'] ?? '')" />
                                <x-input-error class="mt-2" :messages="$errors->get('mail_username')" />
                            </div>

                            <!-- Password -->
                            <div class="md:col-span-2">
                                <x-input-label for="mail_password" value="Contraseña / App Password" />
                                <x-text-input id="mail_password" name="mail_password" type="password" class="mt-1 block w-full" />
                                <x-input-error class="mt-2" :messages="$errors->get('mail_password')" />
                                <p class="text-xs text-gray-500 mt-1">La contraseña no se muestra por seguridad. Déjalo en blanco para no cambiarla.</p>
                            </div>

                            <!-- From Address -->
                            <div>
                                <x-input-label for="mail_from_address" value="Email Remitente" />
                                <x-text-input id="mail_from_address" name="mail_from_address" type="email" class="mt-1 block w-full" :value="old('mail_from_address', $settings['mail_from_address'] ?? '')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('mail_from_address')" />
                            </div>
                            
                            <!-- From Name -->
                            <div>
                                <x-input-label for="mail_from_name" value="Nombre Remitente" />
                                <x-text-input id="mail_from_name" name="mail_from_name" type="text" class="mt-1 block w-full" :value="old('mail_from_name', $settings['mail_from_name'] ?? config('app.name'))" required />
                                <x-input-error class="mt-2" :messages="$errors->get('mail_from_name')" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                Guardar Configuración
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
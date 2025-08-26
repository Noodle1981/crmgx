<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Grupo Xamanen CRM') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-light-text antialiased">

        {{-- Contenedor principal con el FONDO ANIMADO --}}
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 
                    bg-gradient-to-br from-aurora-purple via-aurora-blue to-aurora-cyan 
                    animate-gradient-xy bg-[size:400%_400%]">
            
            {{-- Logo con el efecto de texto en gradiente --}}
            <div>
                <a href="/">
                    <h1 class="font-headings text-4xl font-bold bg-gradient-to-r from-aurora-cyan to-light-text bg-clip-text text-transparent">
                        CMR GX
                    </h1>
                </a>
            </div>

            {{-- El panel de cristal para el formulario --}}
            <div class="w-full sm:max-w-md mt-8 px-6 py-8
                        bg-gray-900/70 backdrop-blur-xl
                        border border-white/10 shadow-2xl
                        overflow-hidden rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
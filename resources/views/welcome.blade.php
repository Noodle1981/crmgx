<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Grupo Xamanen CRM') }} - La Nueva Era de la Gestión de Clientes</title>

    <!-- Fonts y Font Awesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Poppins:wght@700;800&display=swap" rel="stylesheet">
    {{-- ¡RECUERDA PONER TU KIT ID DE FONT AWESOME AQUÍ! --}}
    <script src="https://kit.fontawesome.com/TU_KIT_DE_FONT_AWESOME.js" crossorigin="anonymous"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-white"> {{-- Texto principal blanco --}}
    {{-- Fondo naranja sólido --}}
    <div class="min-h-screen w-full bg-orange-xamanen relative overflow-hidden">
        <div id="particles-js" class="absolute inset-0"></div>
        <div class="relative z-10 flex flex-col min-h-screen">
            <header class="w-full">
                <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
                    {{-- Logo EP.png y texto Grupo Xamanen --}}
                    <a href="/" class="flex items-center space-x-2"> {{-- <-- Añade esto aquí --}}
    <div class="bg-white p-1 rounded-md shadow">
        <img src="{{ asset('img/logo.png') }}" alt="EP Consultora Logo" class="block h-9 w-auto">
    </div>
    <span class="font-headings text-2xl font-bold text-white"> & Grupo Xamanen</span>
</a>
                    
                    {{-- BOTONES SIEMPRE VISIBLES PARA VISITANTES --}}
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}"><x-primary-button class="bg-white text-orange-xamanen hover:bg-gray-100">Login</x-primary-button></a>
                    </div>
                </nav>
            </header>
            <main class="flex-grow flex items-center justify-center text-center px-4 relative">
                {{-- Imagen vertical decorativa (posicionada absolutamente) --}}
                <img src="{{ asset('img/logotipos.png') }}" alt="Figuras de higiene y seguridad"
                     class="absolute right-0 top-1/2 -translate-y-1/2 h-[80vh] object-contain opacity-20 hidden md:block z-0">
                
                <div class="max-w-4xl relative z-10"> {{-- z-10 para asegurar que el texto esté sobre la imagen --}}
                    <h1 class="font-headings text-4xL md:text-6xl lg:text-7xl font-extrabold text-white mb-4">
                        CRM. <br/>
                        <span class="text-white">HSE 4.0 & Deals.</span> {{-- Sin gradiente para el span --}}
                    </h1>
                    <p class="max-w-2xl mx-auto text-lg md:text-xl text-white text-opacity-80 mb-8">Version 1.0.0</p>
                    
                    <!-- Información de Roles -->
                    <div class="grid md:grid-cols-2 gap-6 mb-8 max-w-3xl mx-auto">
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6 border-2 border-white/20 hover:bg-white/20 transition">
                            <div class="text-4xl mb-3">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-2">Administrador</h3>
                            <p class="text-sm text-white/80">
                                Gestión completa del sistema, usuarios, configuraciones y monitoreo de rendimiento
                            </p>
                        </div>
                        
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6 border-2 border-white/20 hover:bg-white/20 transition">
                            <div class="text-4xl mb-3">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-2">Usuario CRM</h3>
                            <p class="text-sm text-white/80">
                                Gestión de leads, clientes, oportunidades de venta y seguimiento de actividades
                            </p>
                        </div>
                    </div>
                    
                    <a href="{{ route('login') }}">
                        {{-- Estilo del botón "Comienza Ahora" --}}
                        <button class="px-10 py-4 font-bold text-orange-xamanen rounded-full bg-white transition-all duration-300 hover.:scale-105 hover:shadow-2xl hover:shadow-white/40">Iniciar Sesión</button>
                    </a>
                </div>
            </main>
            <footer class="w-full py-6 text-center text-sm text-white text-opacity-60">
                <p>&copy; {{ date('Y') }}Plataforma Creada por Grupo Xamanen. Todos los derechos reservados.</p>
            </footer>
        </div>
    </div>

    {{-- SCRIPT PARA LAS PARTÍCULAS (no necesita cambios, el blanco se verá bien en naranja) --}}
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            particlesJS('particles-js', {"particles":{"number":{"value":80,"density":{"enable":true,"value_area":800}},"color":{"value":"#ffffff"},"shape":{"type":"circle"},"opacity":{"value":0.5,"random":false},"size":{"value":3,"random":true},"line_linked":{"enable":true,"distance":150,"color":"#ffffff","opacity":0.4,"width":1},"move":{"enable":true,"speed":6,"direction":"none","random":false,"straight":false,"out_mode":"out","bounce":false}},"interactivity":{"detect_on":"canvas","events":{"onhover":{"enable":true,"mode":"repulse"},"onclick":{"enable":true,"mode":"push"},"resize":true},"modes":{"repulse":{"distance":100,"duration":0.4},"push":{"particles_nb":4}}},"retina_detect":true});
        });
    </script>
</body>
</html>
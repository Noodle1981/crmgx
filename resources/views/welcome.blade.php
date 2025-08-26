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
<body class="font-sans antialiased text-light-text">
    <div class="min-h-screen w-full bg-gradient-to-br from-aurora-purple via-aurora-blue to-aurora-cyan animate-gradient-xy bg-[size:400%_400%] relative">
        <div id="particles-js" class="absolute inset-0"></div>
        <div class="relative z-10 flex flex-col min-h-screen">
            <header class="w-full">
                <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
                    <a href="/" class="font-headings text-2xl font-bold bg-gradient-to-r from-aurora-cyan to-light-text bg-clip-text text-transparent">Grupo Xamanen</a>
                    
                    {{-- BOTONES SIEMPRE VISIBLES PARA VISITANTES --}}
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="font-semibold text-light-text-muted hover:text-light-text transition">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"><x-primary-button>Regístrate</x-primary-button></a>
                        @endif
                    </div>
                </nav>
            </header>
            <main class="flex-grow flex items-center justify-center text-center px-4">
                <div class="max-w-4xl">
                    <h1 class="font-headings text-4xl md:text-6xl lg:text-7xl font-extrabold text-light-text mb-4">
                        Gestiona tu Futuro. <br/>
                        <span class="bg-gradient-to-r from-aurora-cyan to-aurora-blue bg-clip-text text-transparent">Cierra más Deals.</span>
                    </h1>
                    <p class="max-w-2xl mx-auto text-lg md:text-xl text-light-text-muted mb-8">Nuestra plataforma CRM combina un diseño futurista con herramientas potentes para llevar tus ventas al siguiente nivel.</p>
                    <a href="{{ route('register') }}">
                        <button class="px-10 py-4 font-bold text-dark-void rounded-full bg-gradient-to-r from-aurora-blue to-aurora-cyan transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-aurora-cyan/40">Comienza Ahora</button>
                    </a>
                </div>
            </main>
            <footer class="w-full py-6 text-center text-sm text-light-text-muted">
                <p>&copy; {{ date('Y') }} Grupo Xamanen. Todos los derechos reservados.</p>
            </footer>
        </div>
    </div>

    {{-- SCRIPT PARA LAS PARTÍCULAS --}}
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            particlesJS('particles-js', {"particles":{"number":{"value":80,"density":{"enable":true,"value_area":800}},"color":{"value":"#ffffff"},"shape":{"type":"circle"},"opacity":{"value":0.5,"random":false},"size":{"value":3,"random":true},"line_linked":{"enable":true,"distance":150,"color":"#ffffff","opacity":0.4,"width":1},"move":{"enable":true,"speed":6,"direction":"none","random":false,"straight":false,"out_mode":"out","bounce":false}},"interactivity":{"detect_on":"canvas","events":{"onhover":{"enable":true,"mode":"repulse"},"onclick":{"enable":true,"mode":"push"},"resize":true},"modes":{"repulse":{"distance":100,"duration":0.4},"push":{"particles_nb":4}}},"retina_detect":true});
        });
    </script>
</body>
</html>
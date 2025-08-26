import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            // ===============================================================
            // INICIO DE LA PERSONALIZACIÓN "AURORA GLASS"
            // ===============================================================

            // 1. PALETA DE COLORES PERSONALIZADA
            colors: {
                'aurora-purple': '#65005E',
                'aurora-blue': '#3C84CE',
                'aurora-cyan': '#30EEE2',
                'aurora-red-pop': '#FF1919',
                'dark-void': '#0A0C10',
                'light-text': '#F0F2F5',
                'light-text-muted': 'rgba(240, 242, 245, 0.7)',
            },

            // 2. FUENTES PERSONALIZADAS
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans], // Fuente base para el cuerpo
                headings: ['Poppins', 'sans-serif'], // Fuente para títulos
            },

            // 3. ANIMACIÓN PARA EL GRADIENTE DEL FONDO
            keyframes: {
                'gradient-xy': {
                    '0%, 100%': {
                        'background-position': '0% 50%'
                    },
                    '50%': {
                        'background-position': '100% 50%'
                    },
                }
            },
            animation: {
                'gradient-xy': 'gradient-xy 15s ease infinite',
            },

            // 4. VALORES DE BLUR PARA EL EFECTO GLASSMORPHISM
            // Puedes añadir más si los necesitas, ej: 'sm': '4px', etc.
            backdropBlur: {
                'xl': '24px',
            }

            // ===============================================================
            // FIN DE LA PERSONALIZACIÓN
            // ===============================================================
        },
    },

    plugins: [forms],
};
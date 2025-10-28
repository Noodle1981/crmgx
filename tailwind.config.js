import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class', // O 'media' si lo prefieres

    theme: {
        extend: { // <-- LA CLAVE ESTÁ AQUÍ
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                headings: ['Poppins', 'sans-serif'], // Ejemplo de fuente personalizada
            },
            colors: {
                // Tu paleta de colores de marca
                'primary': {
                    'light': '#FFAC4E',
                    'DEFAULT': '#FF8F12', // Naranja principal
                    'dark': '#D97706',
                },
                'secondary': '#4B5563',

                // Esquema de colores para el modo claro (fondo blanco)
                'background': '#FFFFFF',
                'surface': '#F9FAFB',
                'text-main': '#1F2937',
                'text-muted': '#6B7280',

                // Esquema de colores para el modo oscuro (¡los que te gustan!)
                'dark-background': '#021745ff', // Un gris muy oscuro
                'dark-surface': '#ef680eff',    // Un gris un poco más claro para tarjetas
                'dark-text-main': '#F9FAFB',
                'dark-text-muted': '#9CA3AF',
            },
        },
    },

    plugins: [forms],
};
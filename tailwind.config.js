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
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Outfit', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                noguchi: {
                    dark: '#192230',      // Deep Obsidian Navy Base
                    surface: '#2c2f38',   // Anthracite Charcoal
                    slate: '#3d474e',     // Muted Steel Slate
                    yellow: '#ffcd00',    // Electric Solar Yellow
                    yellowHover: '#e6b800',
                    light: '#FFFDF0',
                    canvas: '#F7F8FA',
                },
                brand: {
                    primary: '#ffcd00',
                    secondary: '#192230',
                    dark: '#2c2f38',
                    slate: '#3d474e',
                    light: '#FFFDF0',
                },
                // Aliasing amber and orange so existing blade classes adopt the Noguchi Solar Yellow palette
                amber: {
                    50: '#FFFDF0',
                    100: '#FFF9C4',
                    200: '#FFF176',
                    300: '#FFEE58',
                    400: '#FFD700',
                    500: '#ffcd00',       // Noguchi Solar Yellow
                    600: '#e6b800',
                    700: '#cc9900',
                    800: '#2c2f38',       // Dark Charcoal
                    900: '#192230',       // Deep Navy
                    950: '#0f1620',
                },
                orange: {
                    50: '#F7F8FA',
                    100: '#E4E7EB',
                    200: '#CBD1D8',
                    300: '#3d474e',       // Slate
                    400: '#2c2f38',       // Charcoal
                    500: '#ffcd00',       // Solar Yellow
                    600: '#192230',       // Deep Navy
                    700: '#141b26',
                    800: '#0e131b',
                    900: '#070a0e',
                },
            },
        },
    },

    plugins: [forms],
};

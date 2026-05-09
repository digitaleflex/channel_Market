import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import lineClamp from '@tailwindcss/line-clamp';

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
                brand: {
                    primary: '#f59e0b',
                    secondary: '#111827',
                    accent: '#f97316',
                }
            }
        },
    },

    plugins: [forms, lineClamp],
};

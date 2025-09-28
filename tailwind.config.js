import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './vendor/namu/wirechat/resources/views/**/*.blade.php',
        './vendor/namu/wirechat/src/Livewire/**/*.php',
    ],
    safelist: [
        'bg-blue-100', 'bg-blue-500', 'bg-red-500', 'bg-green-500',
        'text-blue-600', 'text-white', 'bg-white/10', 'bg-white/20',
        'from-purple-300', 'to-indigo-400',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms, typography],
};

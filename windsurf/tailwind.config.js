import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    safelist: [
        // Cores de badges de etapas
        'bg-blue-100', 'text-blue-800', 'border-blue-200',
        'bg-green-100', 'text-green-800', 'border-green-200',
        'bg-yellow-100', 'text-yellow-800', 'border-yellow-200',
        'bg-red-100', 'text-red-800', 'border-red-200',
        'bg-purple-100', 'text-purple-800', 'border-purple-200',
        'bg-gray-100', 'text-gray-800', 'border-gray-200',
        'bg-indigo-100', 'text-indigo-800', 'border-indigo-200',
        'bg-pink-100', 'text-pink-800', 'border-pink-200',
        'bg-orange-100', 'text-orange-800', 'border-orange-200',
        // Cores de botões de transição
        'bg-blue-600', 'hover:bg-blue-700',
        'bg-green-600', 'hover:bg-green-700',
        'bg-yellow-500', 'hover:bg-yellow-600',
        'bg-red-600', 'hover:bg-red-700',
        'bg-purple-600', 'hover:bg-purple-700',
        'bg-gray-600', 'hover:bg-gray-700',
        'bg-indigo-600', 'hover:bg-indigo-700',
        'bg-pink-500', 'hover:bg-pink-600',
        'bg-orange-500', 'hover:bg-orange-600',
        'text-white', 'shadow-sm',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};

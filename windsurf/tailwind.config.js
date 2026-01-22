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
        // Cores primary personalizadas
        'bg-primary-50', 'bg-primary-100', 'bg-primary-200', 'bg-primary-300',
        'bg-primary-400', 'bg-primary-500', 'bg-primary-600', 'bg-primary-700',
        'bg-primary-800', 'bg-primary-900', 'bg-primary-950',
        'text-primary-50', 'text-primary-100', 'text-primary-200', 'text-primary-300',
        'text-primary-400', 'text-primary-500', 'text-primary-600', 'text-primary-700',
        'text-primary-800', 'text-primary-900', 'text-primary-950',
        'hover:bg-primary-600', 'hover:bg-primary-700', 'hover:bg-primary-800',
        'border-primary-200', 'border-primary-500', 'border-primary-600',
        'ring-primary-500', 'focus:ring-primary-500', 'focus:border-primary-500',
        'shadow-primary-600/20', 'shadow-primary-500/20',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: '#f0f9ff',
                    100: '#e0f2fe',
                    200: '#bae6fd',
                    300: '#7dd3fc',
                    400: '#38bdf8',
                    500: '#0ea5e9',
                    600: '#0284c7',
                    700: '#0369a1',
                    800: '#075985',
                    900: '#0c4a6e',
                    950: '#082f49',
                },
                secondary: {
                    50: '#f8fafc',
                    100: '#f1f5f9',
                    200: '#e2e8f0',
                    300: '#cbd5e1',
                    400: '#94a3b8',
                    500: '#64748b',
                    600: '#475569',
                    700: '#334155',
                    800: '#1e293b',
                    900: '#0f172a',
                    950: '#020617',
                }
            },
            backdropBlur: {
                xs: '2px',
            },
        },
    },

    darkMode: 'class',

    plugins: [forms],
};

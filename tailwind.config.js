import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                accent: {
                    DEFAULT: '#4F46E5', // Indigo-600
                    hover:   '#4338CA', // Indigo-700
                    light:   '#EEF2FF', // Indigo-50
                    muted:   '#818CF8', // Indigo-400
                },
                success: { DEFAULT: '#10B981', light: '#D1FAE5', dark: '#059669' },
                danger:  { DEFAULT: '#EF4444', light: '#FEE2E2', dark: '#DC2626' },
                warning: { DEFAULT: '#F59E0B', light: '#FEF3C7', dark: '#D97706' },
                info:    { DEFAULT: '#3B82F6', light: '#DBEAFE', dark: '#2563EB' },
            },
        },
    },

    plugins: [forms],
};

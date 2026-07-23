import defaultTheme from 'tailwindcss/defaultTheme';
import colors from 'tailwindcss/colors';
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
            colors: {
                // Deep navy for dark sections/nav.
                ink: '#0B1220',
                // Light page background.
                cloud: '#F7F8FB',
                // Full default Tailwind shade scales are kept intact (indigo-50..950 etc.
                // are used throughout the rest of the app) - only DEFAULT is pinned to the
                // design system's brand hex so `bg-indigo`/`text-indigo`/etc. work without
                // a shade number, alongside the untouched `indigo-600` and friends.
                indigo: { ...colors.indigo, DEFAULT: '#4F46E5' },
                cyan: { ...colors.cyan, DEFAULT: '#22D3EE' },
                slate: { ...colors.slate, DEFAULT: '#64748B' },
                amber: { ...colors.amber, DEFAULT: '#FBBF24' },
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                body: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['"Space Grotesk"', ...defaultTheme.fontFamily.sans],
                mono: ['"JetBrains Mono"', ...defaultTheme.fontFamily.mono],
            },
        },
    },

    plugins: [forms],
};

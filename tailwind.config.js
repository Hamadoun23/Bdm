import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.{js,jsx}',
    ],

    theme: {
        extend: {
            fontFamily: {
                // UI courante : pile système neutre et lisible (esprit
                // shadcn/21st.dev). Futura reste réservée à la marque
                // (logo/wordmark) via `font-brand`, pas au texte d'appli.
                sans: [
                    '-apple-system',
                    'BlinkMacSystemFont',
                    '"Segoe UI"',
                    'Inter',
                    'Roboto',
                    '"Helvetica Neue"',
                    'Arial',
                    ...defaultTheme.fontFamily.sans,
                ],
                brand: [
                    'Futura',
                    '"Futura PT"',
                    '"Futura Std"',
                    '"Century Gothic"',
                    '"Trebuchet MS"',
                    ...defaultTheme.fontFamily.sans,
                ],
            },
            colors: {
                gda: {
                    brun: '#381419',
                    gris: '#303030',
                    cuivre: '#b26440',
                    orange: '#FF6A3A',
                    blanc: '#ffffff',
                },
            },
            boxShadow: {
                card: '0 1px 2px 0 rgb(0 0 0 / 0.04), 0 1px 3px 0 rgb(0 0 0 / 0.04)',
            },
        },
    },

    plugins: [forms],
};

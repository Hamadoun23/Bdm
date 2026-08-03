import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import path from 'path';

export default defineConfig({
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
        },
    },
    plugins: [
        laravel({
            // app.js (Alpine) reste l'entrée des vues Blade pas encore converties
            // (ex. layouts/guest.blade.php) ; app.jsx est la nouvelle entrée
            // Inertia/React. Les deux coexistent pendant la conversion
            // progressive (Phase 3) — retirer app.js une fois toutes les
            // pages qui l'utilisent encore converties.
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/app.jsx'],
            refresh: true,
        }),
        react(),
    ],
});

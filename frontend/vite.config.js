import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import path from 'path';

// Le build produit frontend/dist ; Django lit le manifeste `.vite/manifest.json`
// pour retrouver les fichiers hachés (cf. backend/core/templatetags/vite.py).
// En développement, Django pointe directement vers ce serveur (port 5173).
export default defineConfig({
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'src'),
        },
    },
    plugins: [react()],
    build: {
        manifest: true,
        outDir: 'dist',
        emptyOutDir: true,
        rollupOptions: {
            input: 'src/app.jsx',
        },
    },
    server: {
        port: 5173,
        strictPort: true,
        // Django sert les pages ; seuls les assets viennent d'ici.
        origin: 'http://localhost:5173',
        cors: true,
    },
});

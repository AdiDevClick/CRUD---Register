import { defineConfig } from "vite"

export default defineConfig({
    server: {
        origin: 'http://localhost:5173' // Règle le problème des chemins dans le CSS
    },
    base: '/assets',
    build: {
        copyPublicDir: false,
        outDir: 'public/assets',
        assetsDir: '',
        manifest: true,
        rollupOptions: {
            input: [
                'resources/main.js',
                'resources/main.css',
            ]
        },
    },
})
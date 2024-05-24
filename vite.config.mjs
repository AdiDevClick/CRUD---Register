import { defineConfig } from "vite"

export default defineConfig({
    server: {
        origin: 'http://localhost:5173' // Règle le problème des chemins dans le CSS
    },
    base: '/assets',
    build: {
        manifest: true,
        copyPublicDir: false,
        outDir: 'public/assets',
        assetsDir: '',
        rollupOptions: {
            input: [
                'resources/main.js',
                'scripts/toaster.js',
            ]
        },
    },
})
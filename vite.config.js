import { defineConfig } from "vite"

export default defineConfig({
    server: {
        origin: 'https://localhost/recettes/' // Règle le problème des chemins dans le CSS
    },
    base: '/assets/',
    build: {
        copyPublicDir: false,
        outDir: 'public/assets',
        assetsDir: '',
        manifest: true,
        rollupOptions: {
            input: [
                'resources/main.js',
                'scripts/toaster.js',
            ]
        },
    },
})
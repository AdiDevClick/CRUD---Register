import { defineConfig } from 'vite';
import autoprefixer from 'autoprefixer';
import postcssNesting from 'postcss-nesting';
import eslint from 'vite-plugin-eslint';

export default defineConfig({
    server: {
        // origin: 'https://http://192.168.1.181:5173',
        origin: 'https://127.0.0.1:80',
        // origin: 'https://localhost:5173', // Règle le problème des chemins dans le CSS
        warmup: {
            clientFiles: ['./src/components/*.vue', './src/utils/big-utils.js'],
            ssrFiles: ['./src/server/modules/*.js'],
        },
        // hmr: {
        // host: '192.168.1.181',
        // host: '192.168.1.97',
        // host: '192.168.1.100',
        // host: 'vite.adi',
        // }
    },
    base: '/assets/',
    build: {
        manifest: true,
        copyPublicDir: false,
        outDir: 'public/assets',
        // minify: 'terser',
        // terserOptions: {
        //     compress: {
        //         drop_console: true,
        //     },
        // },
        assetsDir: '',
        rollupOptions: {
            input: ['resources/main.js', 'scripts/toaster.js'],
            output: {
                chunkFileNames: 'js/[name]-[hash].js',
                entryFileNames: 'js/[name]-[hash].js',
                assetFileNames: '[ext]/[name]-[hash].[ext]',
            },
        },
        sourcemap: false,
    },
    css: {
        postcss: {
            plugins: [
                autoprefixer({
                    grid: 'autoplace',
                    // browsers: [
                    // '>1%',
                    // 'last 5 versions',
                    //'not ie < 9',  React doesn't support IE8 anyway
                    // ],
                }), // add options if needed
                // postcssNesting(),
            ],
        },
    },
    plugins: [eslint()],
});

import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: ['resources/css/app.css', 'resources/ts/main.ts'],
            refresh: true
        })
    ],
    resolve: {
        alias: {
            '@': "/resources/ts/"
        }
    },
    server: {
        hmr: {
            host: 'localhost'
        },
        watch: {
            usePolling: true
        }

    }
})

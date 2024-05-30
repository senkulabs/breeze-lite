import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { svelte } from '@sveltejs/vite-plugin-svelte';
import { resolve } from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        svelte({})
    ],
    resolve: {
        alias: {
            '@': resolve(__dirname, 'resource/js')
        },
        extensions: ['.js', '.svelte', '.json']
    }
});

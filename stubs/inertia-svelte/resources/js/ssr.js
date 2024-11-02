import { createInertiaApp } from '@inertiajs/svelte';
import createServer from '@inertiajs/svelte/server';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

createServer((page) =>
    createInertiaApp({
        page,
        resolve: (name) =>
            resolvePageComponent(`./Pages/${name}.svelte`, import.meta.glob('./Pages/**/*.svelte')),
    }),
);
import { createInertiaApp } from '@inertiajs/svelte';
import createServer from '@inertiajs/svelte/server';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { render } from 'svelte/server';
import { route } from '../../vendor/tightenco/ziggy';

createServer((page) =>
    createInertiaApp({
        page,
        resolve: (name) =>
            resolvePageComponent(`./Pages/${name}.svelte`, import.meta.glob('./Pages/**/*.svelte')),
        setup({ App, props }) {
            global.route = (name, params, absolute) => 
                route(name, params, absolute, {
                    ...page.props.ziggy,
                    location: new URL(page.props.ziggy.location)
                });
                
            return render(App, { props })
        },
    }),
);
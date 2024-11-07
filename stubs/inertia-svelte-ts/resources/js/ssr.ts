import { createInertiaApp, type ResolvedComponent } from '@inertiajs/svelte';
import createServer from '@inertiajs/svelte/server';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { render } from 'svelte/server';
import { route } from 'ziggy-js';

createServer((page) =>
    createInertiaApp({
        page,
        resolve: (name) =>
            resolvePageComponent(
                `./Pages/${name}.svelte`,
                import.meta.glob<ResolvedComponent>('./Pages/**/*.svelte')
            ),
        setup({ App, props }) {
            // @ts-expect-error
            globalThis.route<RouteName> = (name, params, absolute) =>
                route(name, params as any, absolute, {
                    ...page.props.ziggy,
                    location: page.props.ziggy.location,
                });
            return render(App, { props });
        },
    })
);

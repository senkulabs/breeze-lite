import { createInertiaApp, type ResolvedComponent } from '@inertiajs/svelte';
import createServer from '@inertiajs/svelte/server';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { render } from 'svelte/server';
import { route } from '../../vendor/tightenco/ziggy';

createServer((page) =>
    createInertiaApp({
        page,
        resolve: (name) =>
            resolvePageComponent(
                `./Pages/${name}.svelte`,
                import.meta.glob<ResolvedComponent>('./Pages/**/*.svelte')
            ),
        setup({ App, props }) {
            /* eslint-disable */
            // @ts-expect-error
            globalThis.route<RouteName> = (name, params, absolute) =>
                route(name, params as any, absolute, {
                    ...page.props.ziggy,
                    location: page.props.ziggy.location,
                });
            /* eslint-enable */

            return render(App, { props });
        },
    })
);

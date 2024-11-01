<?php

namespace SenkuLabs\Breeze\Console;

use Illuminate\Filesystem\Filesystem;
use Laravel\Breeze\Console\InstallsInertiaStacks as LaravelBreezeInstallsInertiaStacks;

trait InstallsInertiaStacks
{
    use LaravelBreezeInstallsInertiaStacks;

    /**
     * Install the Inertia Svelte stack.
     *
     * @return int|null
     */
    protected function installInertiaSvelteStack()
    {
        Log::info('Current base path in breeze-lite: '. base_path());
        Log::info('Current vendor path in breeze-lite: '. base_path('vendor'));

        // Install Inertia...
        if (! $this->requireComposerPackages(['inertiajs/inertia-laravel:^1.0', 'laravel/sanctum:^4.0', 'tightenco/ziggy:^2.0'])) {
            return 1;
        }

        // NPM Packages...
        $this->updateNodePackages(function ($packages) {
            return [
                '@inertiajs/svelte' => '^2.0.0-beta.2',
                '@tailwindcss/forms' => '^0.5.7',
                '@sveltejs/vite-plugin-svelte' => '^4.0.0',
                'autoprefixer' => '^10.4.20',
                'postcss' => '^8.4.33',
                'tailwindcss' => '^3.4.10',
                'svelte' => '^5.0',
            ] + $packages;
        });

        if ($this->option('typescript')) {
            // TODO: I'm not familiar with TypeScript. Wait and see.
        }

        if ($this->option('eslint')) {
            // TODO: I'm not familiar with eslint. Wait and see.
        }

        // Providers...
        (new Filesystem)->copyDirectory(base_path('vendor/laravel/breeze/stubs/inertia-common/app/Providers'), app_path('Providers'));

        // Controllers...
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Controllers'));
        (new Filesystem)->copyDirectory(base_path('vendor/laravel/breeze/stubs/inertia-common/app/Http/Controllers'), app_path('Http/Controllers'));

        // Requests...
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Requests'));
        (new Filesystem)->copyDirectory(base_path('vendor/laravel/breeze/stubs/default/app/Http/Requests'), app_path('Http/Requests'));

        // Middleware...
        $this->installMiddleware([
            '\App\Http\Middleware\HandleInertiaRequests::class',
            '\Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class',
        ]);

        (new Filesystem)->ensureDirectoryExists(app_path('Http/Middleware'));
        copy(base_path('vendor/laravel/breeze/stubs/inertia-common/app/Http/Middleware/HandleInertiaRequests.php'), app_path('Http/Middleware/HandleInertiaRequests.php'));

        // Views...
        copy(__DIR__.'/../../stubs/inertia-svelte/resources/views/app.blade.php', resource_path('views/app.blade.php'));

        @unlink(resource_path('views/welcome.blade.php'));

        // Components + Pages...
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Components'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Layouts'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages'));

        if ($this->option('typescript')) {
            // TODO: I'm not familiar with typescript. So, wait and see.
        } else {
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-svelte/resources/js/Components', resource_path('js/Components'));
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-svelte/resources/js/Layouts', resource_path('js/Layouts'));
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-svelte/resources/js/Pages', resource_path('js/Pages'));
        }

        if (! $this->option('dark')) {
            // NOTE: Dark mode can wait.
        }

        // Tests...
        if (! $this->installTests()) {
            return 1;
        }

        if ($this->option('pest')) {
            (new Filesystem)->copyDirectory(base_path('vendor/laravel/breeze/stubs/inertia-common/pest-tests/Feature'), base_path('tests/Feature'));
        } else {
            (new Filesystem)->copyDirectory(base_path('vendor/laravel/breeze/stubs/inertia-common/tests/Feature'), base_path('tests/Feature'));
        }

        // Routes...
        copy(base_path('vendor/laravel/breeze/stubs/inertia-common/routes/web.php'), base_path('routes/web.php'));
        copy(base_path('vendor/laravel/breeze/stubs/inertia-common/routes/auth.php'), base_path('routes/auth.php'));

        // Tailwind / Vite...
        copy(base_path('vendor/laravel/breeze/stubs/default/resources/css/app.css'), resource_path('css/app.css'));
        copy(base_path('vendor/laravel/breeze/stubs/default/postcss.config.js'), base_path('postcss.config.js'));
        copy(base_path('vendor/laravel/breeze/stubs/inertia-common/tailwind.config.js'), base_path('tailwind.config.js'));
        copy(__DIR__.'/../../stubs/inertia-svelte/vite.config.js', base_path('vite.config.js'));

        if ($this->option('typescript')) {
            // TODO: I'm not familiar with typescript. So, wait and see.
        } else {
            copy(base_path('vendor/laravel/breeze/stubs/inertia-common/jsconfig.json'), base_path('jsconfig.json'));
            copy(__DIR__.'/../../stubs/inertia-svelte/resources/js/app.js', resource_path('js/app.js'));
        }

        if ($this->option('ssr')) {
            // TODO: I'm not familiar with ssr. So, wait and see.
        }

        $this->components->info('Installing and building Node dependencies.');

        if (file_exists(base_path('pnpm-lock.yaml'))) {
            $this->runCommands(['pnpm install', 'pnpm run build']);
        } elseif (file_exists(base_path('yarn.lock'))) {
            $this->runCommands(['yarn install', 'yarn run build']);
        } elseif (file_exists(base_path('bun.lockb'))) {
            $this->runCommands(['bun install', 'bun run build']);
        } else {
            $this->runCommands(['npm install', 'npm run build']);
        }

        $this->line('');
        $this->components->info('Breeze Lite scaffolding installed successfully.');
    }
}

<?php

namespace SenkuLabs\Breeze\Console;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
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
        // Install Inertia...
        if (! $this->requireComposerPackages(['inertiajs/inertia-laravel:^2.0', 'laravel/sanctum:^4.0', 'tightenco/ziggy:^2.0'])) {
            return 1;
        }

        // NPM Packages...
        $this->updateNodePackages(function ($packages) {
            return [
                '@inertiajs/svelte' => '^2.0.0',
                '@tailwindcss/forms' => '^0.5.7',
                '@tailwindcss/vite' => '^4.0.0',
                '@sveltejs/vite-plugin-svelte' => '^6.0.0',
                'tailwindcss' => '^4.0.0',
                'svelte' => '^5.0',
                'svelte-check' => '^4.0.0',
            ] + $packages;
        });

        if ($this->option('typescript')) {
            $this->updateNodePackages(function ($packages) {
                return [
                    'typescript' => '^5.5',
                    '@tsconfig/svelte' => '^5.0.2',
                ] + $packages;
            });
        }

        if ($this->option('eslint')) {
            $this->updateNodePackages(function ($packages) {
                return [
                    'eslint' => '^9.7.0',
                    'eslint-plugin-svelte' => '^2.36.0',
                    'eslint-config-prettier' => '^9.1.0',
                    'prettier' => '^3.3.3',
                    'prettier-plugin-organize-imports' => '^4.0.0',
                    'prettier-plugin-tailwindcss' => '^0.6.5',
                    'prettier-plugin-svelte' => '^3.2.7',
                ] + $packages;
            });

            if ($this->option('typescript')) {
                $this->updateNodePackages(function ($packages) {
                    return [
                        '@types/eslint' => '^9.6.0',
                        'typescript-eslint' => '^8.0.0',
                    ] + $packages;
                });


                $this->updateNodeScripts(function ($scripts) {
                    return $scripts + [
                        'check' => 'svelte-check --tsconfig ./tsconfig.json',
                        'check:watch' => 'svelte-check --tsconfig ./tsconfig.json --watch',
                        'format' => 'prettier --write resources/js',
                        'lint' => 'eslint resources/js --fix',
                    ];
                });

                copy(__DIR__.'/../../stubs/inertia-svelte-ts/eslint.config.js', base_path('eslint.config.js'));
            } else {
                $this->updateNodeScripts(function ($scripts) {
                    return $scripts + [
                        'check' => 'svelte-check --tsconfig ./jsconfig.json',
                        'check:watch' => 'svelte-check --tsconfig ./jsconfig.json --watch',
                        'format' => 'prettier --write resources/js',
                        'lint' => 'eslint resources/js --fix',
                    ];
                });

                copy(__DIR__.'/../../stubs/inertia-svelte/eslint.config.js', base_path('eslint.config.js'));
            }

            copy(__DIR__.'/../../stubs/inertia-svelte/.prettierrc', base_path('.prettierrc'));
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
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-svelte-ts/resources/js/Components', resource_path('js/Components'));
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-svelte-ts/resources/js/Layouts', resource_path('js/Layouts'));
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-svelte-ts/resources/js/Pages', resource_path('js/Pages'));
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-svelte-ts/resources/js/types', resource_path('js/types'));
        } else {
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-svelte/resources/js/Components', resource_path('js/Components'));
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-svelte/resources/js/Layouts', resource_path('js/Layouts'));
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-svelte/resources/js/Pages', resource_path('js/Pages'));
        }

        if (! $this->option('dark')) {
            $this->removeDarkClasses((new Finder)
                ->in(resource_path('js'))
                ->name(['*.svelte', '*.svelte'])
                ->notName(['Welcome.svelte'])
            );
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
        copy(__DIR__.'/../../stubs/inertia-svelte/vite.config.js', base_path('vite.config.js'));
        copy(__DIR__.'/../../stubs/default/resources/css/app.css', resource_path('css/app.css'));
        $this->replaceInFile('.js', '.svelte', resource_path('css/app.css'));

        if ($this->option('typescript')) {
            copy(__DIR__.'/../../stubs/inertia-svelte-ts/tsconfig.json', base_path('tsconfig.json'));
            copy(__DIR__.'/../../stubs/inertia-svelte-ts/resources/js/app.ts', resource_path('js/app.ts'));

            if (file_exists(resource_path('js/app.js'))) {
                unlink(resource_path('js/app.js'));
            }

            if (file_exists(resource_path('js/bootstrap.js'))) {
                rename(resource_path('js/bootstrap.js'), resource_path('js/bootstrap.ts'));
            }

            $this->replaceInFile('.js', '.ts', base_path('vite.config.js'));
            $this->replaceInFile('.js', '.ts', resource_path('views/app.blade.php'));
            $this->replaceInFile('"vite build', '"tsc && vite build', base_path('package.json'));
        } else {
            copy(__DIR__.'/../../stubs/inertia-svelte/jsconfig.json', base_path('jsconfig.json'));
            copy(__DIR__.'/../../stubs/inertia-svelte/resources/js/app.js', resource_path('js/app.js'));
        }

        if ($this->option('ssr')) {
            $this->installInertiaSvelteSsrStack();
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

    protected function installInertiaSvelteSsrStack()
    {
        if ($this->option('typescript')) {
            copy(__DIR__.'/../../stubs/inertia-svelte-ts/resources/js/ssr.ts', resource_path('js/ssr.ts'));
            $this->replaceInFile("input: 'resources/js/app.ts',", "input: 'resources/js/app.ts',".PHP_EOL."            ssr: 'resources/js/ssr.ts',", base_path('vite.config.js'));
        } else {
            copy(__DIR__.'/../../stubs/inertia-svelte/resources/js/ssr.js', resource_path('js/ssr.js'));
            $this->replaceInFile("input: 'resources/js/app.js',", "input: 'resources/js/app.js',".PHP_EOL."            ssr: 'resources/js/ssr.js',", base_path('vite.config.js'));
        }

        $this->configureZiggyForSsr();

        $this->replaceInFile('vite build', 'vite build && vite build --ssr', base_path('package.json'));
        $this->replaceInFile('/node_modules', '/bootstrap/ssr'.PHP_EOL.'/node_modules', base_path('.gitignore'));
    }
}

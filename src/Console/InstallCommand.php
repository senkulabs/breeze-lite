<?php

namespace SenkuLabs\Breeze\Console;

use Symfony\Component\Console\Attribute\AsCommand;

use Laravel\Breeze\Console\InstallCommand as LaravelBreezeInstallCommand;
use Laravel\Breeze\Console\InstallsApiStack;
use Laravel\Breeze\Console\InstallsBladeStack;
use Laravel\Breeze\Console\InstallsLivewireStack;

use function Laravel\Prompts\select;

#[AsCommand(name: 'breeze:install')]
class InstallCommand extends LaravelBreezeInstallCommand
{
    use InstallsApiStack, InstallsBladeStack, InstallsInertiaStacks, InstallsLivewireStack;

    /**
     * Execute the console command.
     *
     * @return int|null
     */
    public function handle()
    {
        if ($this->argument('stack') === 'vue') {
            return $this->installInertiaVueStack();
        } elseif ($this->argument('stack') === 'react') {
            return $this->installInertiaReactStack();
        } elseif ($this->argument('stack') === 'svelte') {
            return $this->installInertiaSvelteStack();
        } elseif ($this->argument('stack') === 'api') {
            return $this->installApiStack();
        } elseif ($this->argument('stack') === 'blade') {
            return $this->installBladeStack();
        } elseif ($this->argument('stack') === 'livewire') {
            return $this->installLivewireStack();
        } elseif ($this->argument('stack') === 'livewire-functional') {
            return $this->installLivewireStack(true);
        }

        $this->components->error('Invalid stack. Supported stacks are [blade], [livewire], [livewire-functional], [react], [vue], [svelte], and [api].');

        return 1;
    }

    /**
     * Prompt for missing input arguments using the returned questions.
     *
     * @return array
     */
    protected function promptForMissingArgumentsUsing()
    {
        return [
            'stack' => fn () => select(
                label: 'Which Breeze stack would you like to install?',
                options: [
                    'blade' => 'Blade with Alpine',
                    'livewire' => 'Livewire (Volt Class API) with Alpine',
                    'livewire-functional' => 'Livewire (Volt Functional API) with Alpine',
                    'react' => 'React with Inertia',
                    'vue' => 'Vue with Inertia',
                    'svelte' => 'Svelte with Inertia',
                    'api' => 'API only',
                ],
                scroll: 5,
            ),
        ];
    }
}

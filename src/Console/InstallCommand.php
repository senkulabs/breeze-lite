<?php

namespace SenkuLabs\Breeze\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Laravel\Breeze\Console\InstallCommand as LaravelBreezeInstallCommand;
use Laravel\Breeze\Console\InstallsApiStack;
use Laravel\Breeze\Console\InstallsBladeStack;
use Laravel\Breeze\Console\InstallsLivewireStack;

use function Laravel\Prompts\select;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\multiselect;

#[AsCommand(name: 'breeze:install')]
class InstallCommand extends LaravelBreezeInstallCommand
{
    use InstallsApiStack, InstallsBladeStack, InstallsInertiaStacks, InstallsLivewireStack;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'breeze:install {stack : The development stack that should be installed (blade,livewire,livewire-functional,react,svelte,vue,api)}
                            {--dark : Indicate that dark mode support should be installed}
                            {--pest : Indicate that Pest should be installed}
                            {--ssr : Indicates if Inertia SSR support should be installed}
                            {--typescript : Indicates if TypeScript is preferred for the Inertia stack}
                            {--eslint : Indicates if ESLint with Prettier should be installed}
                            {--composer=global : Absolute path to the Composer binary which should be used to install packages}';

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

    /**
     * Interact further with the user if they were prompted for missing arguments.
     *
     * @return void
     */
    protected function afterPromptingForMissingArguments(InputInterface $input, OutputInterface $output)
    {
        $stack = $input->getArgument('stack');

        if (in_array($stack, ['react', 'svelte', 'vue'])) {
            collect(multiselect(
                label: 'Would you like any optional features?',
                options: [
                    'dark' => 'Dark mode',
                    'ssr' => 'Inertia SSR',
                    'typescript' => 'TypeScript',
                    'eslint' => 'ESLint with Prettier',
                ],
                hint: 'Use the space bar to select options.'
            ))->each(fn ($option) => $input->setOption($option, true));
        } elseif (in_array($stack, ['blade', 'livewire', 'livewire-functional'])) {
            $input->setOption('dark', confirm(
                label: 'Would you like dark mode support?',
                default: false
            ));
        }

        $input->setOption('pest', select(
            label: 'Which testing framework do you prefer?',
            options: ['Pest', 'PHPUnit'],
            default: 'Pest',
        ) === 'Pest');
    }
}

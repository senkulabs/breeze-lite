<script>
    import GuestLayout from '@/Layouts/GuestLayout.svelte';
    import InputLabel from '@/Components/InputLabel.svelte';
    import TextInput from '@/Components/TextInput.svelte';
    import InputError from '@/Components/InputError.svelte';
    import Checkbox from '@/Components/Checkbox.svelte';
    import { inertia, useForm } from '@inertiajs/svelte';
    import PrimaryButton from '@/Components/PrimaryButton.svelte';

    let { canResetPassword, status } = $props();

    const form = useForm({
        email: '',
        password: '',
        remember: false
    });

    function submit(e) {
        e.preventDefault();
        $form.post(route('login'), {
            onFinish: () => $form.reset('password')
        });
    }
</script>

<svelte:head>
    <title>Log in</title>
</svelte:head>

<GuestLayout>
    <!-- Session Status -->
    {#if status}
        <div class="mb-4 text-sm font-medium text-green-600">
            {status}
        </div>
    {/if}

    <form onsubmit={submit}>
        <!-- Email Address -->
        <div>
            <InputLabel for="email" value="Email" />
            <TextInput
                id="email"
                class="mt-1 block w-full"
                type="email"
                name="email"
                bind:value={$form.email}
                required
                autocomplete="username"
            />
            <InputError message={$form.errors.email} class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <InputLabel for="password" value="Password" />
            <TextInput
                id="password"
                class="mt-1 block w-full"
                type="password"
                name="password"
                bind:value={$form.password}
                required
                autocomplete="current-password"
            />
            <InputError message={$form.errors.password} class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="mt-4 block">
            <label for="remember-me" class="flex items-center">
                <Checkbox id="remember-me" name="remember" />
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Remember me</span>
            </label>
        </div>

        <div class="mt-4 flex items-center justify-end">
            {#if canResetPassword}
                <a
                    use:inertia
                    href={route('password.request')}
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                >
                    Forgot your password?
                </a>
            {/if}

            <PrimaryButton
                class="ms-4 {$form.processing && 'opacity-25'}"
                disabled={$form.processing}>Log in</PrimaryButton
            >
        </div>
    </form>
</GuestLayout>

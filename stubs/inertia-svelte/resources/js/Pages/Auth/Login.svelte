<script>
    import GuestLayout from '@/Layouts/GuestLayout.svelte';
    import InputLabel from '@/Components/InputLabel.svelte';
    import TextInput from '@/Components/TextInput.svelte';
    import InputError from '@/Components/InputError.svelte';
    import Checkbox from '@/Components/Checkbox.svelte';
    import { inertia, useForm } from '@inertiajs/svelte';
    import PrimaryButton from '@/Components/PrimaryButton.svelte';

    let {
        canResetPassword,
        status
    } = $props();

    const form = useForm({
        email: '',
        password: '',
        remember: false
    });

    function submit(e) {
        e.preventDefault();
        $form.post(route('login'), {
            onFinish: () => $form.reset('password'),
        })
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
            <TextInput id="email" class="block mt-1 w-full" type="email" name="email" bind:value={$form.email} required autocomplete="username" />
            <InputError message={$form.errors.email} class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <InputLabel for="password" value="Password" />
            <TextInput id="password" class="block mt-1 w-full" type="password" name="password" bind:value={$form.password} required autocomplete="current-password"/>
            <InputError message={$form.errors.password} class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <Checkbox id="remember_me" name="remember"/>
                <span class="ms-2 text-sm text-gray-600">Remember me</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            {#if canResetPassword}
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                use:inertia href={route('password.request')}>
                    Forgot your password?
                </a>
            {/if}

            <PrimaryButton disabled={$form.processing} class="ms-4 {$form.processing && 'opacity-25'}">Log in</PrimaryButton>
        </div>
    </form>
</GuestLayout>

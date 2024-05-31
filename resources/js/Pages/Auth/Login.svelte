<script>
    import GuestLayout from '@/Layouts/Guest.svelte';
    import AuthSessionStatus from '@/Components/AuthSessionStatus.svelte';
    import InputLabel from '@/Components/InputLabel.svelte';
    import TextInput from '@/Components/TextInput.svelte';
    import InputError from '@/Components/InputError.svelte';
    import Checkbox from '@/Components/Checkbox.svelte';
    import { inertia, useForm } from '@inertiajs/svelte';
    import PrimaryButton from '@/Components/PrimaryButton.svelte';
    import route from '@/ziggy.js';

    let err = {};
    export let errors = {};
    export let status;

    const form = useForm({
        email: '',
        password: '',
        remember: false
    });

    $: {
        err = errors;
    };

    const onSubmit = () => {
        $form.post(route('login'), {
            onFinish: () => $form.reset('password'),
        });
    };
</script>

<svelte:head>
    <title>Log in</title>
</svelte:head>

<GuestLayout>
    <!-- Session Status -->
    <AuthSessionStatus class="mb-4" status={status} />

    <form on:submit|preventDefault={onSubmit}>

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
            <InputError messages={$form.errors.password} class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <Checkbox id="remember_me" name="remember"/>
                <span class="ms-2 text-sm text-gray-600">Remember me</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            {#if route().has('password.request')}
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" 
                use:inertia href={route('password.request')}>
                    Forgot your password?
                </a>
            {/if}
            
            <PrimaryButton processing={$form.processing} class="ms-3" type="submit">Log in</PrimaryButton>
        </div>
    </form>
</GuestLayout>
<script>
    import GuestLayout from '@/Layouts/Guest.svelte';
    import InputLabel from '@/Components/InputLabel.svelte';
    import TextInput from '@/Components/TextInput.svelte';
    import InputError from '@/Components/InputError.svelte';
    import PrimaryButton from '@/Components/PrimaryButton.svelte';
    import { useForm } from '@inertiajs/svelte';

    import { route } from 'ziggy-js';

    let { status } = $props();

    const form = useForm({
        email: '',
    });

    function onSubmit(event) {
        event.preventDefault();
        $form.post(route('password.email'));
    };
</script>

<svelte:head>
    <title>Forgot Password</title>
</svelte:head>

<GuestLayout>
    <div class="mb-4 text-sm text-gray-600">
        Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
    </div>

    <!-- Session status -->
    {#if status}
        <div class="mb-4 text-sm font-medium text-green-600 dark:text-green-400">
            {status}
        </div>
    {/if}

    <form onsubmit={onSubmit}>
        <!-- Email Address -->
        <div>
            <InputLabel for="email" value="Email"/>
            <TextInput id="email" class="block mt-1 w-full" type="email" name="email" bind:value={$form.email} required autocomplete="username" />
            <InputError message={$form.errors.email} class="mt-2" />
        </div>

        <div class="mt-4 flex items-center justify-end">
            <PrimaryButton class={$form.processing && 'opacity-25'} disabled={$form.processing}>Email Password Reset Link</PrimaryButton>
        </div>
    </form>
</GuestLayout>

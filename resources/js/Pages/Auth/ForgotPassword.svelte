<script>
    import GuestLayout from '@/Layouts/Guest.svelte';
    import AuthSessionStatus from '@/Components/AuthSessionStatus.svelte';
    import InputLabel from '@/Components/InputLabel.svelte';
    import TextInput from '@/Components/TextInput.svelte';
    import InputError from '@/Components/InputError.svelte';
    import PrimaryButton from '@/Components/PrimaryButton.svelte';
    import { useForm } from '@inertiajs/svelte';

    import route from '@/ziggy';

    export let status;

    const form = useForm({
        email: '',
    });

    const onSubmit = () => {
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
    <AuthSessionStatus class="mb-4" status={status} />

    <form on:submit|preventDefault={onSubmit}>
        <!-- Email Address -->
        <div>
            <InputLabel for="email" value="Email"/>
            <TextInput id="email" class="block mt-1 w-full" type="email" name="email" bind:value={$form.email} required autocomplete="username" />
            <InputError message={$form.errors.email} class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <PrimaryButton processing={$form.processing} class="ms-3" type="submit">Email Password Reset Link</PrimaryButton>
        </div>
    </form>
</GuestLayout>
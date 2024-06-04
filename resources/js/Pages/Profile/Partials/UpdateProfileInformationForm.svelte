<script>
    import InputError from '@/Components/InputError.svelte';
    import InputLabel from '@/Components/InputLabel.svelte';
    import PrimaryButton from '@/Components/PrimaryButton.svelte';
    import TextInput from '@/Components/TextInput.svelte';
    import route from '@/ziggy.js';
    import { inertia, useForm, page } from '@inertiajs/svelte';

    export let mustVerifyEmail = false;
    export let status;

    let className;
    export { className as class };

    const user = $page.props.auth.user;

    const form = useForm({
        name: user.name,
        email: user.email,
    });
</script>

<section class="{className}">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Profile Information</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Update your account's profile information and email address.
        </p>
    </header>

    <form on:submit|preventDefault={() => $form.patch(route('profile.update'))} class="mt-6 space-y-6">
        <div>
            <InputLabel for="name" value="Name"/>
            <TextInput id="name" type="text" class="mt-1 block w-full" bind:value={$form.name} required autofocus autocomplete="name"/>
        </div>
    </form>
</section>
<script>
    import { useForm, inertia } from '@inertiajs/svelte';
    import GuestLayout from '@/Layouts/GuestLayout.svelte';
    import InputLabel from '@/Components/InputLabel.svelte';
    import TextInput from '@/Components/TextInput.svelte';
    import InputError from '@/Components/InputError.svelte';
    import PrimaryButton from '@/Components/PrimaryButton.svelte';

    const form = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        terms: false
    });

    const onSubmit = (event) => {
        event.preventDefault();
        $form.post(route('register'), {
            onFinish: () => $form.reset('password', 'password_confirmation')
        });
    };
</script>

<svelte:head>
    <title>Register</title>
</svelte:head>

<GuestLayout>
    <form onsubmit={onSubmit}>
        <!-- Name -->
        <div>
            <InputLabel for="name" value="Name" />
            <TextInput
                id="name"
                class="mt-1 block w-full"
                bind:value={$form.name}
                type="text"
                name="name"
                required
                autofocus
                autocomplete="name"
            />
            <InputError message={$form.errors.name} class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <InputLabel for="email" value="Email" />
            <TextInput
                id="email"
                class="mt-1 block w-full"
                bind:value={$form.email}
                type="email"
                name="email"
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
                bind:value={$form.password}
                type="password"
                required
                autocomplete="new-password"
            />
            <InputError message={$form.errors.password} class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <InputLabel for="password-confirmation" value="Confirm Password" />
            <TextInput
                id="password-confirmation"
                class="mt-1 block w-full"
                bind:value={$form.password_confirmation}
                type="password"
                required
                autocomplete="new-password"
            />
            <InputError message={$form.errors.password} class="mt-2" />
        </div>

        <div class="mt-4 flex items-center justify-end">
            <a
                use:inertia
                href={route('login')}
                class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
            >
                Already Registered?
            </a>

            <PrimaryButton type="submit" class="ms-4" processing={$form.processing}
                >Register</PrimaryButton
            >
        </div>
    </form>
</GuestLayout>

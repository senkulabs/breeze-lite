<script>
    import DangerButton from '@/Components/DangerButton.svelte';
    import InputLabel from '@/Components/InputLabel.svelte';
    import InputError from '@/Components/InputError.svelte';
    import Modal from '@/Components/Modal.svelte';
    import SecondaryButton from '@/Components/SecondaryButton.svelte';
    import TextInput from '@/Components/TextInput.svelte';
    import { useForm } from '@inertiajs/svelte';

    let { class: className = '' } = $props();

    let confirmingUserDeletion = $state(false);

    /** @type {HTMLInputElement} */
    let passwordInput;

    const form = useForm({
        password: ''
    });

    function confirmUserDeletion() {
        confirmingUserDeletion = true;
        setTimeout(() => passwordInput?.focus(), 250);
    }

    function deleteUser(e) {
        e.preventDefault();
        $form.delete(route('profile.destroy'), {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
                // Force scroll to top after redirect because it redirect to welcome page
                window.scrollTo(0, 0);
            },
            onError: () => passwordInput?.focus(),
            onFinish: () => $form.reset()
        });
    }

    function closeModal() {
        confirmingUserDeletion = false;
        $form.clearErrors();
        $form.reset();
    }
</script>

<section class="space-y-6 {className}">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Delete Account</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Once your account is deleted, all of its resources and data will be permanently deleted.
            Before deleting your account, please download any data or information that you wish to
            retain.
        </p>
    </header>

    <DangerButton type="button" onclick={confirmUserDeletion}>Delete Account</DangerButton>

    <Modal onclose={closeModal} show={confirmingUserDeletion}>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Are you sure you want to delete your account?
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Once your account is deleted, all of its resources and data will be permanently
                deleted. Please enter your password to confirm you would like to permanently delete
                your account.
            </p>

            <form onsubmit={deleteUser}>
                <div class="mt-6">
                    <InputLabel for="password" value="Password" class="sr-only" />

                    <TextInput
                        id="password"
                        bind:this={passwordInput}
                        bind:value={$form.password}
                        type="password"
                        class="mt-1 block w-full"
                        placeholder="Password"
                        onkeyup={(event) => {
                            if (event.key === 'Enter') deleteUser();
                        }}
                    />

                    <InputError message={$form.errors.password} class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton onclick={closeModal}>Cancel</SecondaryButton>

                    <DangerButton type="submit" class="ml-3" processing={$form.processing} onclick={deleteUser}>
                        Delete Account
                    </DangerButton>
                </div>
            </form>
        </div>
    </Modal>
</section>

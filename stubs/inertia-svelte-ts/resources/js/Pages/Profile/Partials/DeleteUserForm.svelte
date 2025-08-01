<script lang="ts">
    import { useForm } from '@inertiajs/svelte';
    import DangerButton from '@/Components/DangerButton.svelte';
    import InputError from '@/Components/InputError.svelte';
    import InputLabel from '@/Components/InputLabel.svelte';
    import Modal from '@/Components/Modal.svelte';
    import SecondaryButton from '@/Components/SecondaryButton.svelte';
    import TextInput from '@/Components/TextInput.svelte';

    let { class: className }: { class?: string } = $props();

    let confirmingUserDeletion = $state(false);
    let passwordInput: TextInput;

    const form = useForm({
        password: ''
    });

    function confirmUserDeletion() {
        confirmingUserDeletion = true;
        setTimeout(() => passwordInput?.focus(), 250);
    }

    function deleteUser(e: SubmitEvent) {
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

    <DangerButton onclick={confirmUserDeletion}>Delete Account</DangerButton>

    <Modal show={confirmingUserDeletion} onclose={closeModal}>
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
                        onkeyup={(e: KeyboardEvent) => e.key === 'Enter' && deleteUser()}
                    />

                    <InputError message={$form.errors.password} class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton onclick={closeModal}>Cancel</SecondaryButton>

                    <DangerButton
                        type="submit"
                        class="ms-3 {$form.processing && 'opacity-25'}"
                        disabled={$form.processing}
                    >
                        Delete Account
                    </DangerButton>
                </div>
            </form>
        </div>
    </Modal>
</section>

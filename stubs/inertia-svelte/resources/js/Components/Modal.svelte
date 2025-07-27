<script>
    import { fade } from 'svelte/transition';
    import { cubicIn, cubicOut } from 'svelte/easing';


    let {
        children,
        closeable = true,
        maxWidth = '2xl',
        onclose = () => {},
        show = false
    } = $props();

    const close = () => {
        if (closeable) {
            onclose();
        }
    };

    const closeOnEscape = (e) => {
        if (e.key === 'Escape' && show) {
            close();
        }
    };

    let maxWidthClass = $derived(
        {
            sm: 'sm:max-w-sm',
            md: 'sm:max-w-md',
            lg: 'sm:max-w-lg',
            xl: 'sm:max-w-xl',
            '2xl': 'sm:max-w-2xl'
        }[maxWidth]
    );
</script>

<svelte:window onkeydown={closeOnEscape} />

<!-- Use svelte:body to handle overflow without manual DOM manipulation -->
<svelte:body class:overflow-hidden={show} />

{#if show}
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto px-4 py-6 sm:px-0">
        <!-- Backdrop -->
        <!-- svelte-ignore a11y_click_events_have_key_events -->
        <div
            class="fixed inset-0 bg-gray-500 opacity-75 transition-opacity dark:bg-gray-900"
            in:fade={{ duration: 300, easing: cubicOut }}
            out:fade={{ duration: 200, easing: cubicIn }}
            onclick={handleBackdropClick}
            role="button"
            tabindex="-1"
            aria-label="Close modal"
        ></div>

        <!-- Modal content -->
        <div
            class="relative transform overflow-hidden rounded-lg bg-white shadow-xl transition-all sm:w-full dark:bg-gray-800 {maxWidthClass}"
            in:fade={{ duration: 300, easing: cubicOut }}
            out:fade={{ duration: 200, easing: cubicIn }}
            role="dialog"
            aria-modal="true"
        >
            {@render children()}
        </div>
    </div>
{/if}

<style>
    :global(body.overflow-hidden) {
        overflow: hidden;
    }
</style>

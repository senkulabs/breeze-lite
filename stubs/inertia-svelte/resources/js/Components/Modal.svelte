<script>
    import { onDestroy } from 'svelte';
    import { derived } from 'svelte/store';

    let { children, closeable = true, maxWidth = '2xl', onclose = () => {}, show = false } = $props();

    $effect(() => {
        document.body.style.overflow = show ? 'hidden' : 'visible';
    });

    const close = () => {
        if (closeable) {
            onclose();
        }
    }

    const closeOnEscape = (e) => {
        if (e.key === 'Escape' && show) {
            close();
        }
    }

    onDestroy(() => {
        document.removeEventListener('keydown', closeOnEscape);
        document.body.style.overflow = 'visible';
    });

    let maxWidthClass = $derived(
        {
            'sm': 'sm:max-w-sm',
            'md': 'sm:max-w-md',
            'lg': 'sm:max-w-lg',
            'xl': 'sm:max-w-xl',
            '2xl': 'sm:max-w-2xl'
        }[maxWidth],
    );
</script>

<svelte:window onkeydown={closeOnEscape}/>

{#if show}
<div class="fixed inset-0 z-50 px-4 py-6 overflow-y-auto sm:px-0">
    <!-- svelte-ignore a11y_consider_explicit_label -->
    <button class="fixed inset-0 transition-all transform-select-none" onclick={close()}>
        <div class="absolute inset-0 bg-gray-500 opacity-75 dark:bg-gray-900">&nbsp;</div>
    </button>

    <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:mx-auto {maxWidthClass}">
        {@render children()}
    </div>
</div>
{/if}

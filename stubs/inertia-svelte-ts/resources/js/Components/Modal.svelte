<script lang="ts">
    import type { Snippet } from 'svelte';
    import { fade } from 'svelte/transition';
    import { cubicIn, cubicOut } from 'svelte/easing';

    // svelte-ignore non_reactive_update
    let dialog: HTMLDivElement;

    let {
        children,
        closeable = true,
        maxWidth = '2xl',
        onclose = () => {},
        show = false
    }: {
        children: Snippet;
        closeable?: boolean;
        maxWidth?: 'sm' | 'md' | 'lg' | 'xl' | '2xl';
        onclose?: () => void;
        show?: boolean;
    } = $props();

    let maxWidthClass = $derived(
        {
            sm: 'sm:max-w-sm',
            md: 'sm:max-w-md',
            lg: 'sm:max-w-lg',
            xl: 'sm:max-w-xl',
            '2xl': 'sm:max-w-2xl'
        }[maxWidth]
    );

    $effect(() => {
        if (show) document.body.appendChild(dialog);
        if (document) document.body.style.overflow = show ? 'hidden' : 'visible';

        return () => {
            if (document) document.body.style.overflow = 'visible';
        };
    });

    function close() {
        if (closeable) {
            onclose();
        }
    }

    function closeOnEscape(e: KeyboardEvent) {
        if (e.key === 'Escape' && show) {
            onclose();
        }
    }
</script>

<svelte:window on:keydown={closeOnEscape} />

{#if show}
    <div bind:this={dialog} style:display={show ? 'contents' : 'none'}>
        <div class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0" scroll-region>
            <div
                in:fade={{ duration: 300, easing: cubicOut }}
                out:fade={{ duration: 200, easing: cubicIn }}
            >
                <!-- svelte-ignore a11y_consider_explicit_label -->
                <button class="transform-select-none fixed inset-0 transition-all" onclick={close}>
                    <div class="absolute inset-0 bg-gray-500 opacity-75 dark:bg-gray-900">
                        &nbsp;
                    </div>
                </button>

                <div class="relative mb-6 transform overflow-hidden rounded-lg bg-white shadow-xl transition-all sm:mx-auto sm:w-full dark:bg-gray-800 {maxWidthClass}">
                    {@render children()}
                </div>
            </div>
        </div>
    </div>
{/if}

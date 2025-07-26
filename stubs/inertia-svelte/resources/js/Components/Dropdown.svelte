<script>
    import { cubicIn, cubicOut } from 'svelte/easing';

    let {
        align = 'right',
        content,
        contentClasses = 'py-1 bg-white dark:bg-gray-700',
        trigger,
        width = '48'
    } = $props();

    let open = $state(false);
    let widthClass = $derived({ '48': 'w-48' }[width]);
    let alignmentClasses = $derived(
        align === 'left'
            ? 'ltr:origin-top-left rtl:origin-top-right start-0'
            : align === 'right'
              ? 'ltr:origin-top-right rtl:origin-top-left end-0'
              : 'origin-top'
    );

    function closeOnEscape(e) {
        if (open && e.key === 'Escape') {
            open = false;
        }
    }

    function enter(node, { delay = 0, duration = 200, easing = cubicOut }) {
        return {
            delay,
            duration,
            easing,
            css: (t) => `
                opacity: ${t};
                transform: scale(${0.95 + 0.05 * t});
            `
        };
    }

    function leave(node, { delay = 0, duration = 75, easing = cubicIn }) {
        return {
            delay,
            duration,
            easing,
            css: (t) => `
                opacity: ${t};
                transform: scale(${0.95 + 0.05 * t});
            `
        };
    }
</script>

<svelte:window on:keydown={closeOnEscape} />

<!-- svelte-ignore a11y_click_events_have_key_events -->
<!-- svelte-ignore a11y_no_static_element_interactions -->
<div class="relative">
    <div onclick={() => (open = !open)}>
        {@render trigger()}
    </div>

    <!-- Full Screen Dropdown Overlay -->
    {#if open}
        <div class="fixed inset-0 z-40" onclick={() => (open = false)}></div>
        <div in:enter out:leave>
            <div
                class="absolute z-50 mt-2 rounded-md shadow-lg {widthClass} {alignmentClasses}"
                onclick={() => (open = false)}
            >
                <div class="rounded-md ring ring-black ring-black/20 {contentClasses}">
                    {@render content()}
                </div>
            </div>
        </div>
    {/if}
</div>

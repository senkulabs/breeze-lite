<script>
    let { width = 48, align = 'right', content, contentClasses = 'py-1 bg-white dark:bg-gray-700', trigger } = $props();

    let open = $state(false);
    let widthClass = $derived({ '48': 'w-48'}[width]);
    let alignmentClasses = $derived(
        align === 'left'
        ? 'ltr:origin-top-left rtl:origin-top-right start-0'
        : align === 'right'
            ? 'ltr:origin-top-right rtl:origin-top-left end-0'
            : 'origin-top',
    );

    function closeOnEscape(event) {
        if (open && event.key === 'Escape') {
            open = false;
        }
    };
</script>

<svelte:window onkeydown={closeOnEscape}></svelte:window>

<div class="relative">
    <button onclick={() => open = !open}>
        {@render trigger()}
    </button>

    <!-- Full Screen Dropdown Overlay -->
    {#if open}
        <!-- svelte-ignore a11y_consider_explicit_label -->
        <button class="fixed inset-0 z-40 select-none" onclick={() => open = false}></button>
        <button class="absolute z-50 mt-2 rounded-md shadow-lg {widthClass} {alignmentClasses}"
        onclick={() => open = false}>
            <div class="rounded-md ring-1 ring-black ring-opacity-5 {contentClasses}">
                {@render content()}
            </div>
        </button>
    {/if}
</div>

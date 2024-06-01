<script>
    import { onMount, onDestroy } from 'svelte';

    const closeOnEscape = (e) => {
        if (open.value && e.key === 'Escape') {
            open.value = false;
        }
    };

    onMount(() => document.addEventListener('keydown', closeOnEscape));
    onDestroy(() => document.addEventListener('keydown', closeOnEscape));

    export let width = 48;
    export let align;
    let alignmentClasses;
    switch (align) {
        case 'left':
            alignmentClasses = 'ltr:origin-top-left rtl:origin-top-right start-0';
            break;
        case 'top':
            alignmentClasses = 'origin-top';
            break;
        case 'right':
        default:
            alignmentClasses = 'ltr:origin-top-right rtl:origin-top-left end-0';
            break;
    }

    let open = false;
</script>

<div class="relative">
    <button on:click={() => open = !open}>
        <slot name="trigger"/>
    </button>

    <!-- Full Screen Dropdown Overlay -->
    {#if open}
        <button class="fixed inset-0 z-40 select-none" on:click={() => open = false}></button>
        <button class="absolute z-50 mt-2 rounded-md shadow-lg w-{width} {alignmentClasses}"
        on:click={() => open = false}>
            <div class={`rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white dark:bg-gray-700`}>
                <slot name="content"/>
            </div>
        </button>
    {/if}
</div>

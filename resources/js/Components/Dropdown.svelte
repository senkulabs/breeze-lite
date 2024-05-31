<script>
    import { onMount, onDestroy } from 'svelte';

    export let align = 'right';
    export let width = '48';

    const closeOnEscape = (e) => {
        if (open.value && e.key === 'Escape') {
            open.value = false;
        }
    };

    onMount(() => document.addEventListener('keydown', closeOnEscape));
    onDestroy(() => document.addEventListener('keydown', closeOnEscape));

    let widthClass;
    $: widthClass = {
        48: "w-48",
    }[width.toString()];

    let alignmentClass;
    $: alignmentClass =
        align === 'left' ? 'origin-top-left left-0' :
        align === 'right' ? 'origin-top-right right-0' :
        'origin-top';

    let open = false;
</script>

<div class="relative">
    <button on:click={() => open = !open}>
        <slot name="trigger"/>
    </button>

    <!-- Full Screen Dropdown Overlay -->
    {#if open}
        <button class="fixed inset-0 z-40 select-none" on:click={() => open = false}></button>
    {/if}

    {#if open}
        <button class={`absolute z-50 mt-2 rounded-md shadow-lg ${ widthClass + " " + alignmentClass }`}
        on:click={() => open = false}>
            <div class={`rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white dark:bg-gray-700`}>
                <slot name="content"/>
            </div>
        </button>
    {/if}
</div>

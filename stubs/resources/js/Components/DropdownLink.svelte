<script>
    import { inertia } from "@inertiajs/svelte";

    export let href = '#', method = 'get', type;
</script>

{#if type === "button"}
    <button use:inertia={{ href: href, method: method }} type="button" class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out">
        <slot/>
    </button>
{:else}
    <a use:inertia {href} {method} class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out">
        <slot/>
    </a>
{/if}

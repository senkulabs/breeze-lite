<script lang="ts">
    import type { ComponentProps, Snippet } from 'svelte';
    import { Link } from '@inertiajs/svelte';

    let {
        children,
        ...attrs
    }: {
        children: Snippet;
    } & ComponentProps<Link> = $props();
</script>

<Link
    {...attrs}
    class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none dark:text-gray-300 dark:hover:bg-gray-800 dark:focus:bg-gray-800"
>
    {@render children()}
</Link>

<script>
    import { onMount } from "svelte";
    let { class: className, value = $bindable(), ...attrs } = $props();

    let input;

    export function focus() {
        input?.focus();
    }

    onMount(() => {
        if (attrs.autofocus && input) {
            input.focus();
        }
    });
</script>

<input 
    {...attrs}
    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600 {className}"
    bind:value
    bind:this={input}
/>

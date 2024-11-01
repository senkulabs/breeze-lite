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

<input class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm {className}"
{...attrs}
bind:value
bind:this={input}>

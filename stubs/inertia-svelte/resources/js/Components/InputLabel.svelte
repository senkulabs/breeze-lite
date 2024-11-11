<script>
    let { value, children, class: className, ...attrs } = $props();
</script>

<label {...attrs} class="block text-sm font-medium text-gray-700 dark:text-gray-300 {className}">
    {#if value}
        <span>{value}</span>
    {:else if children}
        {@render children()}
    {/if}
</label>

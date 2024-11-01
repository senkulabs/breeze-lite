<script>
    let { value, children, class: className, ...attrs } = $props();
</script>
<label {...attrs} class="block font-medium text-sm text-gray-700 {className}">
    {#if value}
        <span>{value}</span>
    {:else if children}
        {@render children()}
    {/if}
</label>

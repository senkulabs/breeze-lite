<script>
    export let message;
    let className;
    export { className as class };
</script>

{#if message}
    <span class="text-sm text-red-600 space-y-1 {className}">{ message }</span>
{/if}

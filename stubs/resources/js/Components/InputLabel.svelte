<script>
    export let value;
    let labelFor;
    export { labelFor as for };
</script>
<label for={labelFor} class="block font-medium text-sm text-gray-700">
    {#if value}
        <span>{value}</span>
    {:else}
        <span><slot /></span>
    {/if}
</label>

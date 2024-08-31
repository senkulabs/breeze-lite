<script>
    export let value, name, id;

    let input;

    export const focus = () => input.focus();
    export const select = () => input.select();

    $: props = {
        ...$$restProps,
    };

    export const update = (event) => {
        value = event.target.value;
    };
</script>

<input type="checkbox" {name} {value} {id} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
bind:this={input} on:input={update}/>
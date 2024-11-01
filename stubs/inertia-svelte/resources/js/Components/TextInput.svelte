<script>
    export let value, name, id, autocomplete, required;
    export let type = 'text';
    let className, input;

    export const focus = () => input.focus();
    export const select = () => input.select();

    export { className as class };
    
    export const update = (event) => {
        value = event.target.value;
    };
</script>
<input class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm {className}"
{type} {name} {id} {value} {autocomplete} {required}
bind:this={input}
oninput={update}>
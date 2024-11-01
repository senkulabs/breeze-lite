<script>
    let { checked = $bindable(false), class: className, ...attrs } = $props();
</script>

<input {...attrs} type="checkbox" bind:checked class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 {className}"/>

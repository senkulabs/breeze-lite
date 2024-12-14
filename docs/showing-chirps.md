---
title: Showing Chirps
outline: deep
---

# Showing Chirps

In the previous step we added the ability to create Chirps, now we're ready to display them!

## Retrieving the Chirps

Let's update the `index` method of our `ChirpController` class to pass Chirps from every user to our Index page:

::: code-group
```php [app/Http/Controllers/ChirpController.php]
<?php
// ...
class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Chirps/Index', [
            // // [!code --]
            'chirps' => Chirp::with('user:id,name')->latest()->get(), // [!code ++]
        ]);
    }
// ...
}
```
:::

Here we've used Eloquent's `with` method to [eager-load](https://laravel.com/docs/eloquent-relationships#eager-loading) every Chirp's associated user's ID and name. We've also used the `latest` scope to return the records in reverse-chronological order.

::: info INFO
Returning all Chirps at once won't scale in production. Take a look at Laravel's powerful [pagination](https://laravel.com/docs/pagination) to improve performance.
:::

## Connecting users to Chirps

We've instructed Laravel to return the `id` and `name` property from the `user` relationship so that we can display the name of the Chirp's author without returning other potentially sensitive information such as the author's email address. But, the Chirp's `user` relationship hasn't been defined yet. To fix this, let's add a new ["belongs to"](https://laravel.com/docs/eloquent-relationships#one-to-many-inverse) relationship to our `Chirp` model:

::: code-group
```php [app/Models/Chirp.php]
<?php
// ...
use Illuminate\Database\Eloquent\Relations\BelongsTo; // [!code ++]
 
class Chirp extends Model
{
// ...
    public function user(): BelongsTo // [!code ++]
    { // [!code ++]
        return $this->belongsTo(User::class); // [!code ++]
    } // [!code ++]
}
```
:::

This relationship is the inverse of the "has many" relationship we created earlier on the `User` model.

## Updating our component

Next, let's create a `Chirp` component for our front-end. This component will be responsible for displaying an individual Chirp:

::: code-group
```svelte [resources/js/Components/Chirp.svelte]
<script>
    let { chirp } = $props();
</script>

<div class="p-6 flex space-x-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 -scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
    </svg>
    <div class="flex-1">
        <div class="flex justify-between items-center">
            <div>
                <span class="text-gray-800">{chirp.user.name}</span>
                <small class="ml-2 text-sm text-gray-600">{ new Date(chirp.created_at).toLocaleString() }</small>
            </div>
        </div>
        <p class="mt-4 text-lg text-gray-900">{chirp.message}</p>
    </div>
</div>
```
:::

Finally, we will update our `Chirps/Index` page component to accept the `chirps` prop and render the Chirps below our form using our new component:

::: code-group
```svelte [resources/js/Pages/Chirps/Index.svelte]
<script>
    import Chirp from '@/Components/Chirp.svelte'; // [!code ++]
    import InputError from '@/Components/InputError.svelte';
    import PrimaryButton from '@/Components/PrimaryButton.svelte';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.svelte';
    import { page, useForm } from '@inertiajs/svelte';

    const form = useForm({
        message: ''
    });
    
    function submit(e) {
        e.preventDefault();
        $form.post(route('chirps.store'), {
            onFinish: () => {
                $form.reset();
            }
        });
    }

    const chirps = $page.props.chirps; // [!code ++]
</script>

<svelte:head>
    <title>Chirps</title>
</svelte:head>

<AuthenticatedLayout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <form onsubmit={submit}>
            <textarea required bind:value={$form.message} class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
            placeholder="What's on your mind?"></textarea>
            <InputError class="mt-2" message={$form.errors.message} />
            <PrimaryButton class="mt-4">Chirp</PrimaryButton>
        </form>

        <div class="mt-6 bg-white shadow-sm rounded-lg divide-y"> // [!code ++]
            {#each chirps as chirp} // [!code ++]
                <Chirp {chirp} /> // [!code ++]
            {/each} // [!code ++]
        </div> // [!code ++]
    </div>
</AuthenticatedLayout>
```
:::

Now take a look in your browser to see the message you Chirped earlier!

::: danger STOP ✋
Before go to the next chapter, do you see something weird like when we add the chirp again but it doesn't appeared instantly in our chirp list? But, if we refresh the page then it appears. Why does it happened? 🧐
:::

::: details Here's the answer. 👀


Code in Svelte components is only executed once at creation. We need a `$derived` rune as a reactive computation when dependencies change. In `Chirps/Index.svelte`, the `chirps` variable depends on `$page.props.chirps`. The `$page.props.chirps` is a dependency that will be change each time new chirp added. So, we use `$derived` rune here.

```js
const chirps = $page.props.chirps; // [!code --]
const chirps = $derived($page.props.chirps); // [!code ++]
```
:::

### Extra Credit: Relative dates

In our `Chirp` component we formatted the dates to be human-readable, but we can take that one step further by displaying relative dates using the popular [Day.js](https://day.js.org/) library.

First, install the `dayjs` NPM package:

```bash
npm install dayjs
```

Then we can use this library in our `Chirp` component to display relative dates:

::: code-group
```svelte [resources/js/Components/Chirp.svelte]
<script>
    import dayjs from 'dayjs'; // [!code ++]
    import relativeTime from 'dayjs/plugin/relativeTime'; // [!code ++]

    dayjs.extend(relativeTime); // [!code ++]

    let { chirp } = $props();
</script>

<div class="p-6 flex space-x-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 -scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
    </svg>
    <div class="flex-1">
        <div class="flex justify-between items-center">
            <div>
                <span class="text-gray-800">{chirp.user.name}</span>
                <small class="ml-2 text-sm text-gray-600">{ new Date(chirp.created_at).toLocaleString() }</small> // [!code --]
                <small class="ml-2 text-sm text-gray-600">{ dayjs(chirp.created_at).fromNow() }</small> // [!code ++]
            </div>
        </div>
        <p class="mt-4 text-lg text-gray-900">{chirp.message}</p>
    </div>
</div>
```
:::

Take a look in the browser to see your relative dates.
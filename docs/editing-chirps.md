---
title: Editing Chirps
outline: deep
---

# Editing Chirps

Let's add a feature that's missing from other popular bird-themed microblogging platforms — the ability to edit Chirps!

## Routing

First we will update our routes file to enable the `chirps.update` route for our resource controller:

::: code-group
```php [routes/web.php]
<?php
 ...
Route::resource('chirps', ChirpController::class)
    ->only(['index', 'store']) // [!code --]
    ->only(['index', 'store', 'update']) // [!code ++]
    ->middleware(['auth', 'verified']);
```
:::

Our route table for this controller now looks like this:

| Verb      | URI             | Action | Route Name    |
|-----------|-----------------|--------|---------------|
|       GET |         /chirps |  index |  chirps.index |
|      POST |         /chirps |  store |  chirps.store |
| PUT/PATCH | /chirps/{chirp} | update | chirps.update |

## Updating our component

Next, let's update our `Chirp` component to have an edit form for existing Chirps.

We're going to use the `Dropdown` component that comes with Breeze, which we'll only display to the Chirp author. We'll also display an indication if a Chirp has been edited by comparing the Chirp's `created_at` date with its `updated_at` date:

::: code-group
```svelte [resources/js/Components/Chirp.svelte]
<script>
    import dayjs from 'dayjs';
    import relativeTime from 'dayjs/plugin/relativeTime';
    import Dropdown from './Dropdown.svelte'; // [!code ++]
    import InputError from './InputError.svelte'; // [!code ++]
    import PrimaryButton from './PrimaryButton.svelte'; // [!code ++]
    import { useForm, page } from '@inertiajs/svelte'; // [!code ++]
    
    dayjs.extend(relativeTime);

    let { chirp } = $props();

    let editing = $state(false); // [!code ++]

    const form = useForm({ // [!code ++]
        message: chirp.message // [!code ++]
    }); // [!code ++]

    function submit(e) { // [!code ++]
        e.preventDefault(); // [!code ++]
        $form.patch(route('chirps.update', chirp), { // [!code ++]
            onSuccess: () => { // [!code ++]
                editing = false; // [!code ++]
            } // [!code ++]
        }); // [!code ++]
    } // [!code ++]
</script>

<div class="px-6 flex space-x-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 -scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
    </svg>
    <div class="flex-1">
        <div class="flex justify-between items-center">
            <div>
                <span class="text-gray-800">{chirp.user.name}</span>
                <small class="ml-2 text-sm text-gray-600">{dayjs(chirp.created_at).fromNow()}</small>
                {#if chirp.created_at !== chirp.updated_at} // [!code ++]
                    <small class="text-sm text-gray-600">&middot; edited</small> // [!code ++]
                {/if} // [!code ++]
            </div>
            {#if chirp.user.id === $page.props.auth.user.id} // [!code ++]
                <Dropdown> // [!code ++]
                    {#snippet trigger()} // [!code ++]
                    <!-- svelte-ignore a11y_consider_explicit_label --> // [!code ++]
                    <button> // [!code ++]
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor"> // [!code ++]
                            <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" /> // [!code ++]
                        </svg> // [!code ++]
                    </button> // [!code ++]
                    {/snippet} // [!code ++]
                    {#snippet content()} // [!code ++]
                    <button class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:bg-gray-100 transition duration-150 ease-in-out" onclick={() => editing = true}> // [!code ++]
                        Edit // [!code ++]
                    </button> // [!code ++]
                    {/snippet} // [!code ++]
                </Dropdown> // [!code ++]
            {/if} // [!code ++]
        </div>
        {#if editing} // [!code ++]
            <form onsubmit={submit}> // [!code ++]
                <textarea bind:value={$form.message} class="mt-4 w-full text-gray-900 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"></textarea> // [!code ++]
                <InputError message={$form.errors.message} class="mt-2" /> // [!code ++]
                <div class="space-x-2"> // [!code ++]
                    <PrimaryButton class="mt-4">Save</PrimaryButton> // [!code ++]
                    <button class="mt-4" onclick={() => { // [!code ++]
                        editing = false; // [!code ++]
                        $form.reset(); // [!code ++]
                        $form.clearErrors(); // [!code ++]
                    }}>Cancel</button> // [!code ++]
                </div> // [!code ++]
            </form> // [!code ++]
        {:else} // [!code ++]
            <p class="mt-4 text-lg text-gray-900">{chirp.message}</p> // [!code ++]
        {/if} // [!code ++]
    </div>
</div>
```
:::

## Updating our controller

We can now update the `update` method on our `ChirpController` class to validate the request and update the database. Even though we're only displaying the edit button to the author of the Chirp, we also need to authorize the request to make sure it's actually the author that is updating it:

::: code-group
```php [app/Http/Controllers/ChirpController.php]
<?php
// ...
use Illuminate\Support\Facades\Gate; // [!code ++]
use Inertia\Inertia;
use Inertia\Response;
 
class ChirpController extends Controller
{
 ...
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp) // [!code --]
    public function update(Request $request, Chirp $chirp): RedirectResponse // [!code ++]
    {
        // // [!code --]
        Gate::authorize('update', $chirp); // [!code ++]
 
        $validated = $request->validate([ // [!code ++]
            'message' => 'required|string|max:255', // [!code ++]
        ]); // [!code ++]
 
        $chirp->update($validated); // [!code ++]
 
        return redirect(route('chirps.index')); // [!code ++]
    }
// ...
}
```
:::

::: info INFO
You may have noticed the validation rules are duplicated with the `store` method. You might consider extracting them using [Laravel's Form Request Validation](https://laravel.com/docs/validation#form-request-validation), which makes it easy to re-use validation rules and to keep your controllers light.
:::

## Authorization

By default, the `authorize` method will prevent everyone from being able to update the Chirp. We can specify who is allowed to update it by creating a [Model Policy](https://laravel.com/docs/authorization#creating-policies) with the following command:

```bash
php artisan make:policy ChirpPolicy --model=Chirp
```

This will create a policy class at `app/Policies/ChirpPolicy.php` which we can update to specify that only the author is authorized to update a Chirp:

::: code-group
```php [app/Policies/ChirpPolicy.php]
<?php
// ...
class ChirpPolicy
{
// ...
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Chirp $chirp): bool
    {
        // // [!code --]
        return $chirp->user()->is($user); // [!code ++]
    }
// ...
}
```
:::

## Testing it out

Time to test it out! Go ahead and edit a few Chirps using the dropdown menu. If you register another user account, you'll see that only the author of a Chirp can edit it.

::: danger WAIT ✋
Before go to the next chapter, do you see something weird when we edit and update a chirp then we get the incorrect chirp. Why does it happened? 🧐
:::

::: details Here's the answer. 👀


We render the list of chirps using Chirp component. Each chirp is unique by id. Because we add the edit action in Chirp (and delete action in next chapter), we need to add a unique identify for each list item in order to make Svelte compiler use it to diff when the data changes.

```svelte
{#each chirps as chirp} // [!code --]
{#each chirps as chirp (chirp.id)} // [!code ++]
```
:::
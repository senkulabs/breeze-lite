---
title: Creating Chirps
outline: deep
---

# Creating Chirps

You're now ready to start building your new application! Let's allow our users to post short messages called Chirps.

## Models, migrations, and controllers

To allow users to post Chirps, we will need to create models, migrations, and controllers. Let's explore each of these concepts a little deeper:

- [Models](https://laravel.com/docs/eloquent) provide a powerful and enjoyable interface for you to interact with the tables in your
database.
- [Migrations](https://laravel.com/docs/migrations) allow you to easily create and modify the tables in your database. They ensure that the same database structure exists everywhere that your application runs.
- [Controllers](https://laravel.com/docs/controllers) are responsible for processing requests made to your application and returning a response.

Almost every feature you build will involve all of these pieces working together in harmony, so the `artisan make:model` command can create them all for you at once.

Let's create a model, migration, and resource controller for our Chirps with the following command:

```bash
php artisan make:model -mrc Chirp
```

::: info INFO
You can see all the available options by running the `php artisan make:model --help` command.
:::

This command will create three files for you:

- `app/Models/Chirp.php` - The Eloquent model.
- `database/migrations/<timestamp>_create_chirps_table.php` - The database migration that will create your database table.
- `app/Http/Controllers/ChirpController.php` - The HTTP controller that will take incoming requests and return responses.

## Routing

We will also need to create URLs for our controller. We can do this by adding "routes", which are managed in the routes directory of your project. Because we're using a resource controller, we can use a single Route::resource() statement to define all of the routes following a conventional URL structure.

To start with, we are going to enable two routes:

- The `index` route will display our form and a listing of Chirps.
- The `store` route will be used for saving new Chirps.

We are also going to place these routes behind two [middleware](https://laravel.com/docs/middleware):

- The `auth` middleware ensures that only logged-in users can access the route.
- The `verified` middleware will be used if you decide to enable [email verification](https://laravel.com/docs/verification).

::: code-group
```php [routes/web.php]
<?php
 
use App\Http\Controllers\ChirpController; // [!code ++]
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
 
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
 
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
 
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
 
Route::resource('chirps', ChirpController::class) // [!code ++]
    ->only(['index', 'store']) // [!code ++]
    ->middleware(['auth', 'verified']); // [!code ++]
 
require __DIR__.'/auth.php';
```
:::

This will create the following routes:

| Verb | URI     | Action | Route Name   |
|------|---------|--------|--------------|
|  GET | /chirps |  index | chirps.index |
| POST | /chirps |  store | chirps.store |

::: info INFO
You may view all of the routes for your application by running the `php artisan route:list` command.
:::

Let's test our route and controller by returning a test message from the `index` method of our new `ChirpController` class:

::: code-group
```php [app/Http/Controllers/ChirpController.php]
<?php
// ...
use Illuminate\Http\Response; // [!code ++]
 
class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() // [!code --]
    public function index(): Response // [!code ++]
    {
        // // [!code --]
        return response('Hello, World!'); // [!code ++]
    }
// ...
}
```
:::

If you are still logged in from earlier, you should see your message when navigating to `http://localhost:8000/chirps!`

## Inertia

Not impressed yet? Let's update the `index` method of our `ChirpController` class to render a front-end page component using Inertia. Inertia is what links our Laravel application with our Svelte front-end:

:::code-group
```php [app/Http/Controllers/ChirpController.php]
<?php
 
namespace App\Http\Controllers;
 
use App\Models\Chirp;
use Illuminate\Http\Request;
use Illuminate\Http\Response; // [!code --]
use Inertia\Inertia; // [!code ++]
use Inertia\Response; // [!code ++]
 
class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return response('Hello, World!'); // [!code --]
        return Inertia::render('Chirps/Index', [ // [!code ++]
            // // [!code ++]
        ]); // [!code ++]
    }
// ...
}
```
:::

We can then create our front-end `Chirps/Index` page component with a form for creating new Chirps:

::: code-group
```svelte [resources/js/Pages/Chirps/Index.svelte]
<script>
    import InputError from "@/Components/InputError.svelte";
    import PrimaryButton from "@/Components/PrimaryButton.svelte";
    import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.svelte";
    import { page, useForm } from "@inertiajs/svelte";
    import { route } from 'ziggy-js';

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

    const chirps = $page.props.chirps;
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
    </div>
</AuthenticatedLayout>
```
:::

That's it! Refresh the page in your browser to see your new form rendered in the default layout provided by Breeze!

Now that our front-end is powered by JavaScript, any changes we make to our JavaScript templates will be automatically reloaded in the browser whenever the Vite development server is running via `npm run dev`.

## Navigation Menu

Let's take a moment to add a link to the navigation menu provided by Breeze.

Update the `AuthenticatedLayout` component provided by Breeze to add a menu item for desktop screens:

::: code-group
```svelte [resources/js/Layouts/AuthenticatedLayout.svelte]
<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    <NavLink href={route('dashboard')} active={route().current('dashboard')}>Dashboard</NavLink>
    <NavLink href={route('chirps.index')} active={route().current('chirps.index')}>Chirps</NavLink> // [!code ++]
</div>
```
:::

And also for mobile screens:

::: code-group
```svelte [resources/js/Layouts/AuthenticatedLayout.svelte]
<div class="space-y-1 pb-3 pt-2">
    <ResponsiveNavLink href={route('dashboard')} active={route().current('dashboard')}>
        Dashboard
    </ResponsiveNavLink>
    <ResponsiveNavLink href={route('chirps.index')} active={route().current('chirps.index')}> // [!code ++]
        Chirps // [!code ++]
    </ResponsiveNavLink> // [!code ++]
</div>
```
:::

## Saving the Chirp

Our form has been configured to post messages to the `chirps.store` route that we created earlier. Let's update the `store` method on our `ChirpController` class to validate the data and create a new Chirp:

::: code-group
```php [app/Http/Controllers/ChirpController.php]
<?php
 
namespace App\Http\Controllers;
 
use App\Models\Chirp;
use Illuminate\Http\RedirectResponse; // [!code ++]
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
 
class ChirpController extends Controller
{
 ...
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) // [!code ++]
    public function store(Request $request): RedirectResponse // [!code --]
    {
        // // [!code --]
        $validated = $request->validate([ // [!code ++]
            'message' => 'required|string|max:255', // [!code ++]
        ]); // [!code ++]
 
        $request->user()->chirps()->create($validated); // [!code ++]
 
        return redirect(route('chirps.index')); // [!code ++]
    }
 ...
}
```
:::

We're using Laravel's powerful validation feature to ensure that the user provides a message and that it won't exceed the 255 character limit of the database column we'll be creating.

We're then creating a record that will belong to the logged in user by leveraging a `chirps` relationship. We will define that relationship soon.

Finally, when using Inertia, we can return a redirect response to instruct Inertia to reload our `chirps.index` route.

## Creating a relationship

You may have noticed in the previous step that we called a `chirps` method on the `$request->user()` object. We need to create this method on our User model to define a ["has many"](https://laravel.com/docs/eloquent-relationships#one-to-many) relationship:

::: code-group
```php [app/Models/User.php]
<?php
// ...
use Illuminate\Database\Eloquent\Relations\HasMany; // [!code ++]
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
 
class User extends Authenticatable
{
// ...
    public function chirps(): HasMany // [!code ++]
    { // [!code ++]
        return $this->hasMany(Chirp::class); // [!code ++]
    } // [!code ++]
}
```
:::

::: info INFO
Laravel offers many different types of model relationships that you can read more about in the [Eloquent Relationships](https://laravel.com/docs/eloquent-relationships) documentation.
:::

## Mass assignment protection

Passing all of the data from a request to your model can be risky. Imagine you have a page where users can edit their profiles. If you were to pass the entire request to the model, then a user could edit any column they like, such as an `is_admin` column. This is called a [mass assignment vulnerability](https://en.wikipedia.org/wiki/Mass_assignment_vulnerability).

Laravel protects you from accidentally doing this by blocking mass assignment by default. Mass assignment is very convenient though, as it prevents you from having to assign each attribute one-by-one. We can enable mass assignment for safe attributes by marking them as "fillable".

Let's add the `$fillable` property to our `Chirp` model to enable mass-assignment for the `message` attribute:

::: code-group
```php [app/Models/Chirp.php]
<?php
// ...
class Chirp extends Model
{
// ...
    protected $fillable = [ // [!code ++]
        'message', // [!code ++]
    ]; // [!code ++]
}
```
:::

You can learn more about Laravel's mass assignment protection in the [documentation](https://laravel.com/docs/eloquent#mass-assignment).

## Updating the migration

During the creation of the application, Laravel already applied the default migrations that are included in the `database/migrations` directory. You may inspect the current database structure by using the `php artisan db:show` and `php artisan db:table` commands:

```bash
php artisan db:show
php artisan db:table users
```

So, the only thing missing is extra columns in our database to store the relationship between a `Chirp` and its `User` and the message itself. Remember the database migration we created earlier? It's time to open that file to add some extra columns:

::: code-group
```php [database/migrations/timestamp_create_chirps_table.php]
<?php
// ...
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chirps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // [!code ++]
            $table->string('message'); // [!code ++]
            $table->timestamps();
        });
    }
// ...
};
```
:::

We haven't migrated the database since we added this migration, so let do it now:

```bash
php artisan migrate
```

::: info INFO
Each database migration will only be run once. To make additional changes to a table, you will need to create another migration. During development, you may wish to update an undeployed migration and rebuild your database from scratch using the `php artisan migrate:fresh` command.
:::

## Testing it out

We're now ready to send a Chirp using the form we just created! We won't be able to see the result yet because we haven't displayed existing Chirps on the page.

If you leave the message field empty, or enter more than 255 characters, then you'll see the validation in action.

### Artisan Tinker

This is great time to learn about [Artisan Tinker](https://laravel.com/docs/artisan#tinker), a REPL ([Read-eval-print loop](https://en.wikipedia.org/wiki/Read–eval–print_loop)) where you can execute arbitrary PHP code in your Laravel application.

In your console, start a new tinker session:

```bash
php artisan tinker
```

Next, execute the following code to display the Chirps in your database:

```bash
App\Models\Chirp::all();
```

```bash
=> Illuminate\Database\Eloquent\Collection {#4512
     all: [
       App\Models\Chirp {#4514
         id: 1,
         user_id: 1,
         message: "I'm building Chirper with Laravel!",
         created_at: "2022-08-24 13:37:00",
         updated_at: "2022-08-24 13:37:00",
       },
     ],
   }
```
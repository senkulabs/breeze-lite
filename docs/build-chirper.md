---
title: Build Chirper
---

# Build Chirper

In this section, we will build a Chirper with Laravel + Inertia + Svelte by using Breeze Lite. The content comes from [bootcamp.laravel.com](https://bootcamp.laravel.com) with some tweaks.

## Installing Laravel

If you have already [installed PHP and Composer on your local machine](https://herd.laravel.com/), you may create a new Laravel project via Composer:

```bash
composer create-project laravel/laravel chirper
```

For simplicity, Composer's `create-project` command will automatically create a new SQLite database at `database/database.sqlite` to store your application's data. After the project has been created, start Laravel's local development server using Laravel Artisan's `serve` command:

```bash
cd chirper

php artisan serve
```

Once you have started the Artisan development server, your application will be accessible in your web browser at `http://localhost:8000`.

## Installing Breeze Lite

Next, we will give our application a head-start by installing Breeze Lite, an unofficial [Laravel Breeze](https://laravel.com/docs/starter-kits#laravel-breeze) for Laravel + Inertia + Svelte. According to bootcamp.laravel.com, here's a description of Laravel Breeze.

> A minimal, simple implementation of all of Laravel's authentication features, including login, registration, password reset, email verification, and password confirmation. Once installed, you are welcome to customize the components to suit your needs.

Breeze Lite buit on top Laravel Breeze. So, the options provided by Laravel Breeze still in there but now it adds Svelte (unofficial template from Laravel Breeze). For this tutorial, we will use option for Svelte.

Open a new terminal in your chirper project directory and install Svelte stack with the given commands:

```bash
composer require senkulabs/breeze-lite --dev

php artisan breeze:install svelte
```

Breeze will install and configure your front-end dependencies for you, so we just need to start the Vite development server to enable instant hot-module replacement while we build our application:

```bash
npm run dev
```

If you refresh your new Laravel application in the browser, you should now see a "Register" link at the top-right. Follow that to see the registration form provided by Laravel Breeze. Register yourself an account and log in!
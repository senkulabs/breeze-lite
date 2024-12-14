---
title: Home
description: Unofficial Laravel Breeze for Inertia and Svelte.
---

# Home

Unofficial Laravel Breeze for Inertia + Svelte until Laravel Breeze release the official for Inertia + Svelte. Until then, enjoy! 🍺

> Lite = Laravel + Inertia + Svelte. It's lite and I like it!

## How to use?

Breeze Lite build on top Laravel Breeze. The `php artisan breeze:install` command is a home for Breeze Lite and Laravel Breeze. So, you don't miss the built-in templates provided by Laravel Breeze like Blade with Alpine, Livewire, Vue, React, and API. Use command below to install Breeze Lite in your Laravel project.

```bash
composer require senkulabs/breeze-lite --dev
```

Next, run `php artisan breeze:install` and choose option `Svelte with Inertia`.

## Courtesy

This project use Svelte 5 and Inertia. The Svelte components come from:

- JavaScript: [Michael Sieminski's Pull Request in Laravel/Breeze](https://github.com/laravel/breeze/pull/247).
- TypeScript: [pedroborges/laravel-breeze-svelte](https://github.com/pedroborges/laravel-breeze-svelte).
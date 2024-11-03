---
title: Home
---

# Home

Unofficial Laravel Breeze for Laravel + Inertia + Svelte before Laravel Breeze release the official for Inertia + Svelte. Until then enjoy!

> Lite = Laravel + Inertia + Svelte. I like it!

## How to use?

Breeze Lite build on top Laravel Breeze. The `php artisan breeze:install` command is a home for Breeze Lite and Laravel Breeze. So you don't miss the build in templates provided by Laravel Breeze like Blade with Alpine, Livewire, Vue, React, and API. Use command below to install Breeze Lite in your Laravel project.

```bash
composer require senkulabs/breeze-lite --dev
```

Next, run `php artisan breeze:install` and choose option `Svelte with Inertia`.

## Caveats

Currently, Breeze Lite has problem:

- TypeScript support (compile Svelte component still gets warning).
- Investigate how to use SSR in Inertia and Svelte.
- Dark mode is not ready yet.

But, `eslint with prettier` option works well.

## Courtesy

This project use Svelte 5 and Inertia version 2 (currently beta). The Svelte components come from [pedroborges/laravel-breeze-svelte](https://github.com/pedroborges/laravel-breeze-svelte).
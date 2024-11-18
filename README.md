# Breeze Lite

Unofficial Laravel Breeze for Inertia + Svelte until Laravel Breeze ship the official for Inertia + Svelte. Until then, enjoy! 🍺

> Lite = Laravel + Inertia + Svelte. I like it!

## How to use?

This project build on top Laravel Breeze, so you can still access the build in template provided by Laravel Breeze like Blade, Livewire, Vue, React, and API. The additional template is Svelte 5. 

```
composer require senkulabs/breeze-lite --dev
php artisan breeze:install
# Choose: Svelte with Inertia
```

## Courtesy

This project use Svelte 5 and Inertia. The Svelte components come from:

- JavaScript: [Michael Sieminski's Pull Request in Laravel/Breeze](https://github.com/laravel/breeze/pull/247).
- TypeScript: [pedroborges/laravel-breeze-svelte](https://github.com/pedroborges/laravel-breeze-svelte).
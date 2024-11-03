import { defineConfig } from 'vitepress';

// https://vitepress.dev/reference/site-config
export default defineConfig({
  title: "Breeze Lite",
  description: "Unofficial Laravel Breeze for Laravel + Inertia + Svelte.",
  themeConfig: {
    // https://vitepress.dev/reference/default-theme-config
    nav: [
      { text: 'Home', link: '/' }
    ],

    sidebar: [
      { text: 'Home', link: '/' },
      { text: 'Build Chirper', link: '/build-chirper' },
      { text: 'Creating Chirps', link: '/creating-chirps' },
      { text: 'Showing Chirps', link: '/showing-chirps' },
      { text: 'Editing Chirps', link: '/editing-chirps' },
      { text: 'Deleting Chirps', link: '/deleting-chirps' }
    ],

    socialLinks: [
      { icon: 'github', link: 'https://github.com/senkulabs/breeze-lite' }
    ]
  },
  // deployment
  base: '/',
  cleanUrls: true,
})

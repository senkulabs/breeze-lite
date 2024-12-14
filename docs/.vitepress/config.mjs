import { defineConfig } from 'vitepress';

// https://vitepress.dev/reference/site-config
export default defineConfig({
  title: "Breeze Lite",
  description: "Unofficial Laravel Breeze Inertia + Svelte.",
  transformPageData(pageData) {
    pageData.frontmatter.head ??= [];
    pageData.frontmatter.head.push([
      'meta',
      {
        name: 'og:type',
        content: 'website'
      },
    ]);
    pageData.frontmatter.head.push([
      'meta',
      {
        name: 'og:title',
        content:
          pageData.frontmatter.layout === 'home'
            ? `Breeze Lite`
            : `${pageData.title} | Breeze Lite`
      },
    ]);
    pageData.frontmatter.head.push([
      'meta',
      {
        name: 'og:description',
        content: pageData.frontmatter.description
      },
    ]);
    pageData.frontmatter.head.push([
      'meta',
      {
        name: 'twitter:card',
        content: 'summary'
      },
    ]);
    pageData.frontmatter.head.push([
      'meta',
      {
        name: 'twitter:title',
        content:
          pageData.frontmatter.layout === 'home'
            ? `Breeze Lite`
            : `${pageData.title} | Breeze Lite`
      },
    ]);
    pageData.frontmatter.head.push([
      'meta',
      {
        name: 'twitter:description',
        content: pageData.frontmatter.description
      },
    ]);
  },
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

export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devServer: {
    host: '127.0.0.1',
    port: 3000,
  },
  devtools: { enabled: true },
  app: {
    head: {
      htmlAttrs: { lang: 'vi' },
      title: 'HISOTECH - Giải pháp chuyển đổi số',
      meta: [
        { charset: 'utf-8' },
        { name: 'viewport', content: 'width=device-width, initial-scale=1' },
        { name: 'robots', content: 'index,follow' },
      ],
      link: [],
    },
  },
  css: ['~/assets/css/main.css'],
  vite: {
    server: {
      allowedHosts: ['hst.test'],
    },
  },
  modules: ['@pinia/nuxt', '@nuxt/ui', '@vueuse/motion/nuxt', '@nuxt/image', '@nuxtjs/i18n'],
  i18n: {
    locales: [
      { code: 'vi', language: 'vi-VN', name: 'Tiếng Việt', file: 'vi.json' },
      { code: 'en', language: 'en-US', name: 'English', file: 'en.json' },
    ],
    defaultLocale: 'vi',
    strategy: 'prefix_except_default',
    langDir: 'locales',
    detectBrowserLanguage: false,
  },
  imports: {
    dirs: ['composables/**', 'stores'],
  },
  routeRules: {},
  runtimeConfig: {
    apiBaseUrl: 'http://127.0.0.1:8000/api',
    public: {
      siteUrl: process.env.NUXT_PUBLIC_SITE_URL || 'http://localhost:3000',
    },
  },
})
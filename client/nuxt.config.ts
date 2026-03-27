const DEV_HOST = process.env.NUXT_DEV_HOST || "127.0.0.1";
const DEV_PORT = Number(process.env.NUXT_DEV_PORT || 3000);
const API_BASE_URL = process.env.NUXT_API_BASE_URL || "http://127.0.0.1:8000/api";
const PUBLIC_SITE_URL =
  process.env.NUXT_PUBLIC_SITE_URL || `http://${DEV_HOST}:${DEV_PORT}`;

const APP_HEAD = {
  htmlAttrs: { lang: "vi" },
  title: "HISOTECH - Giải pháp chuyển đổi số",
  meta: [
    { charset: "utf-8" },
    { name: "viewport", content: "width=device-width, initial-scale=1" },
    { name: "robots", content: "index,follow" },
  ],
} as const;

const NUXT_MODULES = [
  "@pinia/nuxt",
  "@nuxt/ui",
  "@vueuse/motion/nuxt",
  "@nuxt/image",
  "@nuxtjs/i18n",
] as const;

const I18N_LOCALES = [
  { code: "vi", language: "vi-VN", name: "Tiếng Việt", file: "vi.json" },
  { code: "en", language: "en-US", name: "English", file: "en.json" },
] as const;

export default defineNuxtConfig({
  compatibilityDate: "2025-07-15",
  devtools: { enabled: true },

  devServer: {
    host: DEV_HOST,
    port: DEV_PORT,
  },

  app: {
    head: APP_HEAD,
  },

  css: ["~/assets/css/main.css"],

  vite: {
    server: {
      allowedHosts: ["hst.test"],
    },
  },

  modules: [...NUXT_MODULES],

  i18n: {
    locales: [...I18N_LOCALES],
    defaultLocale: "vi",
    strategy: "prefix_except_default",
    langDir: "locales",
    detectBrowserLanguage: false,
  },

  imports: {
    dirs: ["composables/**", "stores"],
  },

  runtimeConfig: {
    apiBaseUrl: API_BASE_URL,
    public: {
      siteUrl: PUBLIC_SITE_URL,
    },
  },
});

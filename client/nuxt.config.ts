import tailwindcss from "@tailwindcss/vite";
import botbleFrontend from './modules/botble-frontend/module';

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
	 css: ['./app/assets/css/main.css'],
	vite: {
    server: {
      allowedHosts: ['hst.test'],
    },
		 plugins: [
      tailwindcss(),
    ],
  },
	modules: [botbleFrontend],
	srcDir: 'app/',
	 runtimeConfig: {
   botbleApiInternalBase: 'http://127.0.0.1:8000/api',
  },
})

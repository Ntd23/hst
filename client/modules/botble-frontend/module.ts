// app/modules/botble-frontend/module.ts  (hoặc ./modules/... tuỳ bố đặt)
import { defineNuxtModule } from '@nuxt/kit'

export default defineNuxtModule({
  meta: { name: 'botble-frontend', configKey: 'botbleFrontend' },
  defaults: {
    botbleApiInternalBase: 'http://127.0.0.1:8000/api',
    navCacheMaxAge: 60,
  },
  setup(options, nuxt) {
    // runtimeConfig defaults (server-only)
    nuxt.options.runtimeConfig ||= {}
    ;(nuxt.options.runtimeConfig as any).botbleApiInternalBase ||= options.botbleApiInternalBase

    // Cache menu BFF (menu ít đổi)
    nuxt.options.routeRules ||= {}
    nuxt.options.routeRules['/api/_nuxt/navigation/**'] = {
      cache: { swr: true, maxAge: options.navCacheMaxAge },
    }
  },
})

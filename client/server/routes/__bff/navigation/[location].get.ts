import type {
  NavigationResponse,
  NavigationResponseNormalized,
} from '~~/shared/types/navigation'
import { botbleFetch } from '~~/server/utils/botbleFetch'
import { normalizeNavTree } from '~~/server/utils/navigation'

function resolveLang(event: any): string {
  // Ưu tiên query -> cookie -> default
  const q = getQuery(event)
  const langFromQuery = typeof q.lang === 'string' ? q.lang : ''
  if (langFromQuery) return langFromQuery

  const cookieLang =
    getCookie(event, 'i18n_redirected') ||
    getCookie(event, 'locale') ||
    ''

  return cookieLang || 'vi'
}

export default defineEventHandler(async (event): Promise<NavigationResponseNormalized> => {
  const location = getRouterParam(event, 'location') || ''
  const q = getQuery(event)
  const absolute = q.absolute === '1' || q.absolute === 1
  const lang = resolveLang(event)

  const origin = getRequestURL(event).origin

  // Gọi Laravel/Botble: /api/navigation/{location}?absolute=1&lang=vi
  // (Laravel cần middleware setLocale theo lang để menu/title đúng ngôn ngữ)
  const raw = await botbleFetch<NavigationResponse>(`/navigation/${location}`, {
    query: {
      absolute: absolute ? 1 : 0,
      lang,
    },
    headers: {
      'X-Locale': lang,
    },
  })

  return {
    location: raw.location,
    menu_id: raw.menu_id,
    data: normalizeNavTree(raw.data ?? [], origin),
  }
})

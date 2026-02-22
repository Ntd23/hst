import type { HeaderTopBarResponse } from '~~/shared/types/site'
import { botbleFetch } from '~~/server/utils/botbleFetch'

function getLocaleFromRequest(event: any) {
  // ưu tiên cookie i18n, fallback locale, cuối cùng vi
  return (
    getCookie(event, 'i18n_redirected') ||
    getCookie(event, 'locale') ||
    'vi'
  )
}

export default defineEventHandler(async (event): Promise<HeaderTopBarResponse> => {
  const locale = getLocaleFromRequest(event)

  return botbleFetch<HeaderTopBarResponse>('/site/header-topbar', {
    // 1) query (dễ kiểm soát phía Laravel)
    query: { lang: locale },

    // 2) header (tuỳ bố dùng middleware nào ở Laravel)
    headers: { 'X-Locale': locale },
  })
})

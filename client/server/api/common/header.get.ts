import { apiFetch } from '~~/server/utils/apiFetch'
import { getLocale } from '~~/server/utils/getLocale'

export default defineEventHandler(async (event): Promise<any> => {
  const locale = getLocale(event)

  setResponseHeaders(event, {
    'Cache-Control': 'no-store, no-cache, must-revalidate',
    'Pragma': 'no-cache',
  })

  return apiFetch<any>(event, '/common/header', {
    query: { locale },
    headers: { 'X-Locale': locale },
  })
})

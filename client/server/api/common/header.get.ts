import { apiFetch } from '~~/server/utils/apiFetch'

function getLocaleFromRequest(event: any) {
  // Ưu tiên query param (truyền từ useHeader)
  const query = getQuery(event)
  if (query.locale && typeof query.locale === 'string') {
    return query.locale
  }
  // Fallback: đọc cookie (dùng khi gọi trực tiếp không qua composable)
  return (
    getCookie(event, 'i18n_redirected') ||
    getCookie(event, 'locale') ||
    'vi'
  )
}

export default defineEventHandler(async (event): Promise<any> => {
  const locale = getLocaleFromRequest(event)

  // Không cache API response — admin update thì client nhận ngay
  setResponseHeaders(event, {
    'Cache-Control': 'no-store, no-cache, must-revalidate',
    'Pragma': 'no-cache',
  })

  return apiFetch<any>('/common/header', {
    query: { locale: locale },
    headers: { 'X-Locale': locale },
  })
})


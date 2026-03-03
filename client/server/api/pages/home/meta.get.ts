import { apiFetch } from '~~/server/utils/apiFetch'

export default defineEventHandler(async (event): Promise<any> => {
  const query = getQuery(event)
  const locale = (query.locale as string) || getCookie(event, 'i18n_redirected') || 'vi'

  return apiFetch<any>('/pages/home/meta', {
    query: { locale },
    headers: { 'X-Locale': locale },
  })
})

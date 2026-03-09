import { apiFetch } from '~~/server/utils/apiFetch'
import { getLocale } from '~~/server/utils/getLocale'

export default defineEventHandler(async (event): Promise<any> => {
  const { section } = getRouterParams(event)
  const locale = getLocale(event)

  return apiFetch<any>(`/pages/home/section/${section}`, {
    query: { locale },
    headers: { 'X-Locale': locale },
  })
})

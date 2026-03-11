import { apiFetch } from '~~/server/utils/apiFetch'
import { getLocale } from '~~/server/utils/getLocale'

export default defineEventHandler(async (event): Promise<any> => {
  const { slug } = getRouterParams(event)
  const locale = getLocale(event)

  return apiFetch<any>(event, `/pages/${slug}/meta`, {
    query: { locale },
    headers: { 'X-Locale': locale },
  })
})

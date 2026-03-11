import { apiFetch } from '~~/server/utils/apiFetch'
import { getLocale } from '~~/server/utils/getLocale'

export default defineEventHandler(async (event): Promise<any> => {
  const locale = getLocale(event)

  return apiFetch<any>(event, `/pages/home/meta`, {
    query: { locale },
    headers: { 'X-Locale': locale },
  })
})

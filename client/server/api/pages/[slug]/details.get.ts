import { apiFetch } from '~~/server/utils/apiFetch'
import { getLocale } from '~~/server/utils/getLocale'

export default defineEventHandler(async (event): Promise<any> => {
  const { slug } = getRouterParams(event)
  const locale = getLocale(event)

  if (!slug) {
    return { error: 'Missing slug param' }
  }

  try {
    const res = await apiFetch<any>(event, `/pages/${slug}/details`, {
      query: { locale },
      headers: { 'X-Locale': locale },
    })
    return res
  } catch (err: any) {
    const status = err?.response?.status || err?.status || err?.statusCode
    if (status !== 404) {
      console.error(`[details.get.ts] Error fetching details for slug ${slug}:`, err.message)
    }
    return { data: { details: [] } }
  }
})

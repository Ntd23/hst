import { apiFetch } from '~~/server/utils/apiFetch'
import { getLocale } from '~~/server/utils/getLocale'

export default defineEventHandler(async (event) => {
  const { slug } = getRouterParams(event)
  const locale = getLocale(event)

  if (!slug) {
    return { type: 'unknown', data: null, error: 'Missing slug param' }
  }

  try {
    const response = await apiFetch<any>(event, `/pages/${slug}/details`, {
      query: { locale },
      headers: { 'X-Locale': locale },
    })
    
    // PageDetailController returns { type: 'service' | 'page' | 'blog' | 'unknown', data: ... }
    return response

  } catch (err: any) {
    console.error('Entity resolution error:', err.message)
    return { type: 'unknown', data: null, error: err.message }
  }
})

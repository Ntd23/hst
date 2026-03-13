import { apiFetch } from '~~/server/utils/apiFetch'
import { getLocale } from '~~/server/utils/getLocale'

export default defineEventHandler(async (event) => {
  const { slug } = getRouterParams(event)
  const locale = getLocale(event)

  if (!slug) {
    return { type: 'unknown', data: null, error: 'Missing slug param' }
  }

  // 1. Check if it's a Service first
  try {
    const serviceRes = await apiFetch<any>(event, `/services/${slug}`, {
      query: { locale },
      headers: { 'X-Locale': locale },
    })
    if (serviceRes && !serviceRes.error) {
       return { type: 'service', data: serviceRes }
    }
  } catch (err: any) {
    // ignore
  }

  // 2. Check if it's a Blog Post 
  try {
    const postRes = await apiFetch<any>(event, `/posts/${slug}`, {
      query: { locale },
      headers: { 'X-Locale': locale },
    })
    if (postRes && !postRes.error) {
       return { type: 'blog', data: postRes }
    }
  } catch (err: any) {
    // ignore
  }

  // Fallback unknown
  return { type: 'unknown', data: null }
})

import { apiFetch } from '~~/server/utils/apiFetch'
import { getLocale } from '~~/server/utils/getLocale'

/**
 * Generic page section endpoint.
 * URL: /api/pages/{slug}/sections/{section}
 * Example: /api/pages/home/sections/simple-slider
 */
export default defineEventHandler(async (event): Promise<any> => {
  const params = getRouterParams(event)
  const slug = params.slug
  const section = params.section
  const locale = getLocale(event)

  if (!slug || !section) {
    return { error: 'Missing params', params, contextParams: event.context.params }
  }

  console.log('--- ENTERED section.get.ts ---', slug, section, locale)
  try {
    const res = await apiFetch<any>(`/pages/${slug}/section/${section}`, {
      query: { locale },
      headers: { 'X-Locale': locale },
    })
    console.log('--- FETCH SUCCESS ---')
    return res
  } catch (err: any) {
    console.log('--- FETCH FAILED ---', err.message)
    throw err
  }
})

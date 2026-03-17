import { apiFetch } from '~~/server/utils/apiFetch'
import { getLocale } from '~~/server/utils/getLocale'

export default defineEventHandler(async (event) => {
  const query = getQuery(event)
  const locale = getLocale(event)

  try {
    const response = await apiFetch<any>(event, '/blog/listing', {
      query: { ...query, locale },
      headers: { 'X-Locale': locale },
    })

    return response
  } catch (err: any) {
    throw createError({
      statusCode: err?.response?.status || 500,
      message: err.message || 'Failed to fetch blog listing'
    })
  }
})

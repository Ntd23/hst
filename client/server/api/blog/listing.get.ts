import { proxyApi } from '~~/server/utils/http/apiProxy'

export default defineEventHandler(async (event) => {
  const query = getQuery(event)

  try {
    return await proxyApi<any>(event, {
      path: '/blog/listing',
      query: { ...query },
    })
  } catch (err: any) {
    throw createError({
      statusCode: err?.response?.status || 500,
      message: err.message || 'Failed to fetch blog listing'
    })
  }
})


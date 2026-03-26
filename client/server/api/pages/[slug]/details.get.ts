import { proxyApiWithFallback } from '~~/server/utils/http/apiProxy'

export default defineEventHandler(async (event): Promise<any> => {
  const { slug } = getRouterParams(event)

  if (!slug) {
    return { error: 'Missing slug param' }
  }

  return proxyApiWithFallback<any>(
    event,
    {
      path: `/pages/${slug}/details`,
    },
    {
      onError: (error) => {
        const status =
          error?.response?.status || error?.status || error?.statusCode

        if (status !== 404) {
          console.error(
            `[details.get.ts] Error fetching details for slug ${slug}:`,
            error.message
          )
        }

        return { data: { details: [] } }
      },
    }
  )
})


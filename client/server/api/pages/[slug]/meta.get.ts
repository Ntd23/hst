import { proxyApiWithFallback } from '~~/server/utils/http/apiProxy'

export default defineEventHandler(async (event): Promise<any> => {
  const { slug } = getRouterParams(event)

  return proxyApiWithFallback<any>(
    event,
    {
      path: `/pages/${slug}/meta`,
    },
    {
      onError: (error) => {
        const status =
          error?.response?.status || error?.status || error?.statusCode

        if (status !== 404) {
          console.error(
            `[meta.get.ts] Error fetching meta for slug ${slug}:`,
            error.message
          )
        }

        return null
      },
    }
  )
})


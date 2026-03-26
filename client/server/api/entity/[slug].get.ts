import { proxyApiWithFallback } from '~~/server/utils/http/apiProxy'

export default defineEventHandler(async (event) => {
  const { slug } = getRouterParams(event)

  if (!slug) {
    return { type: 'unknown', data: null, error: 'Missing slug param' }
  }

  return proxyApiWithFallback<any>(
    event,
    {
      path: `/pages/${slug}/details`,
    },
    {
      onError: (error) => {
        console.error('Entity resolution error:', error.message)
        return { type: 'unknown', data: null, error: error.message }
      },
    }
  )
})


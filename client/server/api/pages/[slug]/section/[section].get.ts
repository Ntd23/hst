import { proxyApi } from '~~/server/utils/http/apiProxy'

/**
 * Generic page section endpoint.
 * URL: /api/pages/{slug}/sections/{section}
 * Example: /api/pages/home/sections/simple-slider
 */
export default defineEventHandler(async (event): Promise<any> => {
  const params = getRouterParams(event)
  const slug = params.slug
  const section = params.section

  if (!slug || !section) {
    return { error: 'Missing params', params, contextParams: event.context.params }
  }

  return proxyApi<any>(event, {
    path: `/pages/${slug}/section/${section}`,
  })
})


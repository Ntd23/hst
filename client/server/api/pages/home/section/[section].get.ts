import { proxyApi } from '~~/server/utils/http/apiProxy'

export default defineEventHandler(async (event): Promise<any> => {
  const { section } = getRouterParams(event)

  return proxyApi<any>(event, {
    path: `/pages/home/section/${section}`,
  })
})


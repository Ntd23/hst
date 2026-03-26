import { proxyApi } from '~~/server/utils/http/apiProxy'

export default defineEventHandler(async (event): Promise<any> => {
  return proxyApi<any>(event, {
    path: `/pages/home/meta`,
  })
})


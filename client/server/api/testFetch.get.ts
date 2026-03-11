import { apiFetch } from '~~/server/utils/apiFetch'

export default defineEventHandler(async (event) => {
  try {
    const data = await apiFetch(event, '/pages/home/section/simple-slider', {
      query: { locale: 'vi' }
    })
    return { success: true, data }
  } catch (error: any) {
    return {
      success: false,
      message: error.message,
      url: error.request || error.url,
      status: error.status,
      data: error.data
    }
  }
})

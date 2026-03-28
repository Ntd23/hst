/**
 * Plugin inject favicon từ menu API.
 * Dùng $fetch trực tiếp (không cần useI18n/useFetch trong plugin context).
 */
export default defineNuxtPlugin(async (nuxtApp) => {
  // Lấy locale từ cookie hoặc default
  const locale = useCookie('i18n_redirected').value || 'vi'

  try {
    const data = await $fetch<any>(`/api/menus`, {
      query: { locale },
    })

    const faviconUrl = data?.logo?.favicon
    if (faviconUrl) {
      useHead({
        link: [
          { rel: 'icon', type: 'image/png', href: faviconUrl },
        ],
      })
    }
  } catch (e) {
    // Fallback silently
  }
})

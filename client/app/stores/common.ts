/**
 * Common store — shared data across pages (header, footer, site settings).
 * Fetched once, cached until locale changes.
 */
export const useCommonStore = defineStore('common', () => {
  const headerData = ref<any>(null)
  const footerData = ref<any>(null)
  const _headerLocale = ref<string>('')

  /**
   * Fetch header data. Only re-fetches if locale changed.
   */
  async function fetchHeader(locale: string) {
    if (headerData.value && _headerLocale.value === locale) return
    _headerLocale.value = locale
    headerData.value = await $fetch('/api/common/header', {
      query: { locale },
    })
  }

  /**
   * Reset store — called when locale changes.
   */
  function $reset() {
    headerData.value = null
    footerData.value = null
    _headerLocale.value = ''
  }

  return {
    headerData,
    footerData,
    fetchHeader,
    $reset,
  }
})

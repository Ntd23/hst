/**
 * Header composable — backed by Pinia store + useAsyncData for SSR.
 * SSR awaits header data. Client caches via Pinia across page navigations.
 */
export const useHeader = () => {
  const store = useCommonStore()
  const { locale } = useI18n()

  // useAsyncData ensures SSR waits for data before rendering
  const asyncData = useAsyncData(
    `common-header-${locale.value}`,
    async () => {
      await store.fetchHeader(locale.value)
      return store.headerData
    },
    { dedupe: 'defer' },
  )

  // Re-fetch when locale changes (client only)
  if (import.meta.client) {
    watch(locale, async (newLocale) => {
      store.$reset()
      await store.fetchHeader(newLocale)
    })
  }

  return {
    headerData: computed(() => store.headerData),
    ...asyncData,
  }
}

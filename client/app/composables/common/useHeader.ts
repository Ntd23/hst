/**
 * Header composable — backed by Pinia store + useAsyncData for SSR.
 * SSR awaits header data. Client caches via Pinia across page navigations.
 */
export const useHeader = () => {
  const store = useCommonStore()
  const { localeCode } = useI18nText()

  const asyncData = useAsyncData(
    `common-header-${localeCode.value}`,
    async () => {
      await store.fetchHeader(localeCode.value)
      return store.headerData
    },
    { dedupe: 'defer' },
  )

  if (import.meta.client) {
    watch(localeCode, async (newLocale) => {
      store.$reset()
      await store.fetchHeader(newLocale)
    })
  }

  return {
    headerData: computed(() => store.headerData),
    ...asyncData,
  }
}

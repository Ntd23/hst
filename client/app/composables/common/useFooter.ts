/**
 * Header composable — backed by Pinia store + useAsyncData for SSR.
 * SSR awaits header data. Client caches via Pinia across page navigations.
 */
export const useFooter = () => {
  const store = useCommonStore()
  const { localeCode } = useI18nText()

  const asyncData = useAsyncData(
    `common-footer-${localeCode.value}`,
    async () => {
      await store.fetchFooter(localeCode.value)
      return store.footerData
    },
    { dedupe: 'defer' },
  )

  if (import.meta.client) {
    watch(localeCode, async (newLocale) => {
      store.$reset()
      await store.fetchFooter(newLocale)
    })
  }

  return {
    footerData: computed(() => store.footerData),
    ...asyncData,
  }
}

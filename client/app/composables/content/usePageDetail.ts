/**
 * Reusable composable to fetch page detail data.
 * Usage: const { data } = await usePageDetail('home')
 */
export function usePageDetail<T = any>(pageSlug: string) {
  const { localeCode } = useI18nText()
  
  return useFetch<T>(`/api/pages/${pageSlug}/details`, {
    key: `details-${pageSlug}-${localeCode.value}`,
    query: computed(() => ({ locale: localeCode.value })),
    transform: (res: any) => ({
      ...(res?.data ?? res ?? {}),
      __seo: res?.seo ?? null,
      __entityType: res?.type ?? null,
      __slug: res?.slug ?? pageSlug,
    }),
  })
}

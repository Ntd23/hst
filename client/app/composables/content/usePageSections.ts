/**
 * Reusable composable to fetch all page sections.
 * Usage: const { data } = await usePageSections('home')
 */
export function usePageSections<T = any>(pageSlug: string) {
  const { localeCode } = useI18nText()

  return useFetch<T>(`/api/pages/${pageSlug}/sections`, {
    key: `sections-${pageSlug}-${localeCode.value}`,
    query: computed(() => ({ locale: localeCode.value })),
    transform: (res: any) => res?.data ?? res,
  })
}

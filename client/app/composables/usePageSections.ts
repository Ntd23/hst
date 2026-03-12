/**
 * Reusable composable to fetch all page sections.
 * Usage: const { data } = await usePageSections('home')
 */
export function usePageSections<T = any>(pageSlug: string) {
  const { locale } = useI18n()

  return useFetch<T>(`/api/pages/${pageSlug}/sections`, {
    key: `sections-${pageSlug}-${locale.value}`,
    query: computed(() => ({ locale: locale.value })),
    transform: (res: any) => res?.data ?? res,
  })
}

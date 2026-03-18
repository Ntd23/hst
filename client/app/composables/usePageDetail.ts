/**
 * Reusable composable to fetch all page sections.
 * Usage: const { data } = await usePageSections('home')
 */
export function usePageDetail<T = any>(pageSlug: string) {
  const { locale } = useI18n()
  
  return useFetch<T>(`/api/pages/${pageSlug}/details`, {
    key: `details-${pageSlug}-${locale.value}`,
    query: computed(() => ({ locale: locale.value })),
    transform: (res: any) => res?.data ?? res,
  })
}

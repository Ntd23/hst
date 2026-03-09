/**
 * Reusable composable to fetch any page section.
 * Usage: const { data } = await usePageSection('home', 'simple-slider')
 */
export function usePageSection<T = any>(pageSlug: string, sectionName: string) {
  const { locale } = useI18n()

  return useFetch<T>(`/api/pages/${pageSlug}/section/${sectionName}`, {
    key: `section-${pageSlug}-${sectionName}-${locale.value}`,
    query: computed(() => ({ locale: locale.value })),
  })
}

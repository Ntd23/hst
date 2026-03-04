export const useHeader = () => {
  const { locale } = useI18n()

  const result = useFetch<any>('/api/common/header', {
    key: `common-header-data-${locale.value}`,
    query: computed(() => ({ locale: locale.value })),
    dedupe: 'defer',
    watch: [locale],
  })

  return result
}

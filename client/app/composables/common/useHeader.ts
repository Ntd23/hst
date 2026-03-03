export const useHeader = () => {
  const { locale } = useI18n()

  const result = useFetch<any>('/api/common/header', {
    key: `common-header-data-${locale.value}`,
    query: computed(() => ({ locale: locale.value })),
  })

  // Refetch khi locale đổi (client-side navigation, không reload trang)
  watch(locale, () => result.refresh())

  return result
}

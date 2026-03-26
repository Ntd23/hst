import { computed, unref, type MaybeRef } from 'vue'

export function useBlogListing<T = any>(
  params: MaybeRef<Record<string, any>> = {},
  options: Parameters<typeof useFetch<T>>[1] = {}
) {
  const { localeCode } = useI18nText()

  const query = computed(() => ({
    locale: localeCode.value,
    ...(unref(params) || {}),
  }))

  return useFetch<T>('/api/blog/listing', {
    ...options,
    query,
  })
}

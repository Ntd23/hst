import { computed, unref, type MaybeRef } from 'vue'

export function useBlogListing<T = any>(
  params: MaybeRef<Record<string, any>> = {},
  options: Parameters<typeof useFetch<T>>[1] = {}
) {
  const { locale } = useI18n()

  const query = computed(() => ({
    locale: locale.value,
    ...(unref(params) || {}),
  }))

  return useFetch<T>('/api/blog/listing', {
    ...options,
    query,
  })
}

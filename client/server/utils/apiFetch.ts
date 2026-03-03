export function apiFetch<T>(
  path: string,
  opts: Parameters<typeof $fetch<T>>[1] = {}
) {
  const config = useRuntimeConfig()
  const baseURL = config.apiBaseUrl as string

  return $fetch<T>(path, {
    baseURL,
    ...opts,
  })
}

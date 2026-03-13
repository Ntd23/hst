// export function apiFetch<T>(
//   path: string,
//   opts: Parameters<typeof $fetch<T>>[1] = {}
// ) {
//   const config = useRuntimeConfig()
//   const baseURL = config.apiBaseUrl as string

//   return $fetch<T>(path, {
//     baseURL,
//     ...opts,
//   })
// }

import type { H3Event } from 'h3'

export async function apiFetch<T>(
  event: H3Event,
  path: string,
  opts: Parameters<typeof $fetch<T>>[1] = {}
): Promise<T> {
  const config = useRuntimeConfig()

  const query = getQuery(event)

  const locale =
    (typeof query.locale === 'string' && query.locale) ||
    getCookie(event, 'i18n_redirected') ||
    getCookie(event, 'locale') ||
    'vi'

  return $fetch<T>(path, {
    baseURL: config.apiBaseUrl,
    query: {
      ...(opts.query || {}),
      locale
    },
    headers: {
      ...(opts.headers || {}),
      'X-Locale': locale
    },
    ...opts
  })
}
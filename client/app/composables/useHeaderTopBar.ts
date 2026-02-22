import type { HeaderTopBarResponse } from '~~/shared/types/site'

export function useHeaderTopBar(locale?: string) {
  // nếu không truyền, Nuxt sẽ tự gửi cookie theo request; nhưng key nên tách theo locale nếu có.
  const lang = locale

  return useFetch<HeaderTopBarResponse>('/__bff/site/header-topbar', {
    key: `site:header-topbar:${lang || 'auto'}`,
    query: lang ? { lang } : undefined,
  })
}

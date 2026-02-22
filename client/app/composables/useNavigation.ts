import type { NavigationResponseNormalized } from '~~/shared/types/navigation'

export function useNavigation(location: string, options?: { absolute?: boolean; lang?: string }) {
  const absolute = options?.absolute ?? true
  const lang = options?.lang // nếu không truyền, BFF sẽ tự lấy từ cookie

  return useFetch<NavigationResponseNormalized>(`/__bff/navigation/${location}`, {
    key: `nav:${location}:${absolute ? 1 : 0}:${lang || 'auto'}`,
    query: {
      absolute: absolute ? 1 : 0,
      ...(lang ? { lang } : {}),
    },
  })
}

// server/utils/navigation.ts
import type { NavItem, NavItemNormalized } from '~~/shared/types/navigation'

function parseUrl(url: string, origin: string): URL | null {
  if (!url || url === '#') return null
  try {
    // base origin giúp parse được cả absolute lẫn relative
    return new URL(url, origin)
  } catch {
    return null
  }
}

function isExternalUrl(url: string, origin: string): boolean {
  const u = parseUrl(url, origin)
  if (!u) return false
  return u.origin !== origin
}

function toPath(url: string, origin: string): string {
  if (!url || url === '#') return '#'

  const u = parseUrl(url, origin)
  if (!u) {
    // fallback thô nếu parse fail
    return url.startsWith('/') ? url : `/${url}`
  }

  // nếu external thì giữ nguyên absolute URL để UI dùng <a href>
  if (u.origin !== origin) return url

  return `${u.pathname || '/'}${u.search || ''}${u.hash || ''}`
}

export function normalizeNavTree(items: NavItem[], origin: string): NavItemNormalized[] {
  const walk = (arr: NavItem[]): NavItemNormalized[] => {
    return (arr ?? []).map((it): NavItemNormalized => {
      const external = isExternalUrl(it.url, origin)
      const path = toPath(it.url, origin)

      const children: NavItemNormalized[] = walk(it.children ?? [])

      return {
        ...it,
        external,
        path,
        hasChildren: children.length > 0,
        children,
      }
    })
  }

  return walk(items ?? [])
}

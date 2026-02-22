// app/utils/url.ts
export function toInternalLink(url: string): string {
  if (!url || url === '#') return '#'
  try {
    const u = new URL(url)
    return `${u.pathname || '/'}${u.search || ''}${u.hash || ''}`
  } catch {
    return url.startsWith('/') ? url : `/${url}`
  }
}

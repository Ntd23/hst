import type { H3Event } from 'h3'
import { resolveAppLocale } from '~~/shared/i18n/locale'

/**
 * Extract locale from request — shared across all server API handlers.
 * Priority: query param > cookie > fallback 'vi'
 */
export function getLocale(event: H3Event): string {
  const query = getQuery(event)
  if (query.locale && typeof query.locale === 'string') return resolveAppLocale(query.locale)
  return resolveAppLocale(getCookie(event, 'i18n_redirected') || getCookie(event, 'locale'))
}


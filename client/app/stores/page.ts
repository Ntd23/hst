/**
 * Page store: resolve dynamic routes from Laravel menu URLs.
 */
export const usePageStore = defineStore('page', () => {
  type MenuItem = {
    id?: number | string
    title?: string
    url?: string
    reference_id?: number | string | null
    reference_type?: string | null
    children?: MenuItem[]
  }

  const currentPage = ref<MenuItem | null>(null)
  const loading = ref(false)
  const error = ref<any>(null)

  const normalizePath = (value: string | undefined | null): string => {
    if (!value) return '/'

    let path = value.trim()
    if (!path.startsWith('/')) path = `/${path}`
    path = path.replace(/\/{2,}/g, '/')
    if (path.length > 1) path = path.replace(/\/+$/, '')
    return path.toLowerCase()
  }

  const flattenMenuItems = (items: MenuItem[]): MenuItem[] => {
    return items.flatMap((item) => [item, ...flattenMenuItems(item.children ?? [])])
  }

  /**
   * Resolve page by matching slug to menu item URL.
   */
  async function resolvePage(slug: string, locale: string) {
    loading.value = true
    error.value = null

    try {
      const commonStore = useCommonStore()
      await commonStore.fetchHeader(locale)

      const routePath = normalizePath(slug)
      const menuItems = flattenMenuItems(commonStore.headerData?.main_menu?.items ?? [])
      currentPage.value = menuItems.find((item) => normalizePath(item.url) === routePath) ?? null
    } catch (e: any) {
      error.value = e
      currentPage.value = null
    } finally {
      loading.value = false
    }
  }

  function $reset() {
    currentPage.value = null
    loading.value = false
    error.value = null
  }

  return { currentPage, loading, error, resolvePage, $reset }
})

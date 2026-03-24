/**
 * Page store: resolve dynamic routes from Laravel menu URLs.
 */
import type { MenuItem } from "~~/shared/types/menu";
import { flattenMenuItems, normalizePath } from "~~/shared/utils/menu";

export const usePageStore = defineStore('page', () => {
  const currentPage = ref<MenuItem | null>(null)
  const loading = ref(false)
  const error = ref<any>(null)

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

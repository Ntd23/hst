/**
 * Page store: resolve dynamic routes from Laravel menu URLs.
 */
import type { MenuItem } from "~~/shared/navigation/types";
import { flattenMenuItems, normalizePath } from "~~/shared/navigation/menu";

export const usePageStore = defineStore("page", () => {
  const currentPage = ref<MenuItem | null>(null);
  const loading = ref(false);
  const error = ref<unknown>(null);

  /**
   * Resolve page by matching slug to menu item URL.
   */
  async function resolvePage(slug: string, locale: string) {
    loading.value = true;
    error.value = null;

    try {
      const menuStore = useMenuStore();
      const layoutWidgetStore = useLayoutWidgetStore();

      await Promise.all([
        menuStore.fetchMenus(locale),
        layoutWidgetStore.fetchLayoutWidgets(locale),
      ]);

      const routePath = normalizePath(slug);
      const menuItems = flattenMenuItems(
        menuStore.menuData?.main_menu?.items ?? []
      );

      currentPage.value =
        menuItems.find((item) => normalizePath(item.url) === routePath) ?? null;
    } catch (err) {
      error.value = err;
      currentPage.value = null;
    } finally {
      loading.value = false;
    }
  }

  function $reset() {
    currentPage.value = null;
    loading.value = false;
    error.value = null;
  }

  return {
    currentPage,
    loading,
    error,
    resolvePage,
    $reset,
  };
});

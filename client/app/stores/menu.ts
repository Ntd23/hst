/**
 * Menu store.
 * Fetched once per locale and cached until locale changes.
 */
export const useMenuStore = defineStore("menu", () => {
  const menuData = ref<any>(null);
  const currentLocale = ref("");
  const hasData = computed(() => Boolean(menuData.value));

  async function fetchMenus(locale: string) {
    if (hasData.value && currentLocale.value === locale) {
      return menuData.value;
    }

    currentLocale.value = locale;
    menuData.value = await $fetch("/api/menus", {
      query: { locale },
    });

    return menuData.value;
  }

  function $reset() {
    menuData.value = null;
    currentLocale.value = "";
  }

  return {
    menuData,
    fetchMenus,
    hasData,
    $reset,
  };
});

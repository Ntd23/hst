/**
 * Sidebar widget store.
 * Cached per locale + page type.
 */
export const useSidebarWidgetStore = defineStore("sidebar-widget", () => {
  const sidebarWidgetData = ref<any>(null);
  const currentLocale = ref("");
  const currentType = ref("");
  const hasData = computed(() => Boolean(sidebarWidgetData.value));

  async function fetchSidebarWidgets(type: string, locale: string) {
    if (
      hasData.value &&
      currentLocale.value === locale &&
      currentType.value === type
    ) {
      return;
    }

    currentLocale.value = locale;
    currentType.value = type;
    sidebarWidgetData.value = await $fetch("/api/widgets/sidebar", {
      query: { type, locale },
    });
  }

  function $reset() {
    sidebarWidgetData.value = null;
    currentLocale.value = "";
    currentType.value = "";
  }

  return {
    sidebarWidgetData,
    fetchSidebarWidgets,
    hasData,
    $reset,
  };
});

/**
 * Layout widget store.
 * Fetched once per locale and cached until locale changes.
 */
export const useLayoutWidgetStore = defineStore("layout-widget", () => {
  const layoutWidgetData = ref<any>(null);
  const currentLocale = ref("");
  const hasData = computed(() => Boolean(layoutWidgetData.value));

  async function fetchLayoutWidgets(locale: string) {
    if (hasData.value && currentLocale.value === locale) {
      return layoutWidgetData.value;
    }

    currentLocale.value = locale;
    layoutWidgetData.value = await $fetch("/api/widgets/layout", {
      query: { locale },
    });

    if (import.meta.dev) {
      const data = layoutWidgetData.value || {};
      console.log("Layout widgets fetched:", {
        locale,
        hasData: Boolean(data),
        keys: Object.keys(data),
        counts: {
          menu_sidebar: data.menu_sidebar?.items?.length ?? 0,
          header_top_start: data.header_top_start?.items?.length ?? 0,
          header_top_end: data.header_top_end?.items?.length ?? 0,
          top_footer: data.top_footer?.items?.length ?? 0,
          footer: data.footer?.items?.length ?? 0,
          bottom_footer: data.bottom_footer?.items?.length ?? 0,
        },
      });
    }

    return layoutWidgetData.value;
  }

  function $reset() {
    layoutWidgetData.value = null;
    currentLocale.value = "";
  }

  return {
    layoutWidgetData,
    fetchLayoutWidgets,
    hasData,
    $reset,
  };
});

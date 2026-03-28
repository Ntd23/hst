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
      return;
    }

    currentLocale.value = locale;
    layoutWidgetData.value = await $fetch("/api/widgets/layout", {
      query: { locale },
    });
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

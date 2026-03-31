/**
 * Layout widget composable backed by Pinia + useAsyncData for SSR.
 */
export const useLayoutWidgets = async () => {
  const store = useLayoutWidgetStore();
  const { localeCode } = useI18nText();

  const asyncData = await useAsyncData(
    `widgets-layout-${localeCode.value}`,
    () => store.fetchLayoutWidgets(localeCode.value),
    { dedupe: "defer" }
  );

  const layoutWidgetData = computed(
    () => asyncData.data.value ?? store.layoutWidgetData
  );

  if (import.meta.client) {
    watch(localeCode, async (newLocale) => {
      store.$reset();
      await store.fetchLayoutWidgets(newLocale);
    });
  }

  return {
    layoutWidgetData,
    ...asyncData,
  };
};

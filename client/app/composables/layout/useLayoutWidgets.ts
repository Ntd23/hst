/**
 * Layout widget composable backed by Pinia + useAsyncData for SSR.
 */
export const useLayoutWidgets = () => {
  const store = useLayoutWidgetStore();
  const { localeCode } = useI18nText();

  const asyncData = useAsyncData(
    `widgets-layout-${localeCode.value}`,
    async () => {
      await store.fetchLayoutWidgets(localeCode.value);
      return store.layoutWidgetData;
    },
    { dedupe: "defer" }
  );

  if (import.meta.client) {
    watch(localeCode, async (newLocale) => {
      store.$reset();
      await store.fetchLayoutWidgets(newLocale);
    });
  }

  return {
    layoutWidgetData: computed(() => store.layoutWidgetData),
    ...asyncData,
  };
};

/**
 * Layout widget composable backed by Pinia + useAsyncData for SSR.
 */
export const useLayoutWidgets = () => {
  const store = useLayoutWidgetStore();
  const { localeCode } = useI18nText();
  const { layoutReady } = useAppBoot();

  const asyncData = useAsyncData(
    `widgets-layout-${localeCode.value}`,
    () => store.fetchLayoutWidgets(localeCode.value),
    { dedupe: "defer" }
  );

  const layoutWidgetData = computed(
    () => asyncData.data.value ?? store.layoutWidgetData
  );

  if (import.meta.client) {
    watch(localeCode, async (newLocale) => {
      layoutReady.value = false;
      store.$reset();
      await store.fetchLayoutWidgets(newLocale);
    });
  }

  watchEffect(() => {
    layoutReady.value =
      !asyncData.pending.value && Boolean(asyncData.data.value ?? store.layoutWidgetData);
  });

  return {
    layoutWidgetData,
    isReady: computed(
      () => !asyncData.pending.value && Boolean(layoutWidgetData.value)
    ),
    ...asyncData,
  };
};

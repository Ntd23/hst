/**
 * Sidebar widget composable backed by Pinia + useAsyncData for SSR.
 */
export const useSidebarWidgets = (
  type: MaybeRefOrGetter<string> = "primary"
) => {
  const store = useSidebarWidgetStore();
  const { localeCode } = useI18nText();
  const sidebarType = computed(() => toValue(type) || "primary");

  const asyncData = useAsyncData(
    () => `widgets-sidebar-${sidebarType.value}-${localeCode.value}`,
    async () => {
      await store.fetchSidebarWidgets(sidebarType.value, localeCode.value);
      return store.sidebarWidgetData;
    },
    { dedupe: "defer" }
  );

  if (import.meta.client) {
    watch([sidebarType, localeCode], async ([newType, newLocale]) => {
      store.$reset();
      await store.fetchSidebarWidgets(newType, newLocale);
    });
  }

  return {
    sidebarWidgetData: computed(() => store.sidebarWidgetData),
    ...asyncData,
  };
};

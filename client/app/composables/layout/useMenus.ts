/**
 * Menu composable backed by Pinia + useAsyncData for SSR.
 */
export const useMenus = () => {
  const store = useMenuStore();
  const { localeCode } = useI18nText();

  const asyncData = useAsyncData(
    `menus-${localeCode.value}`,
    () => store.fetchMenus(localeCode.value),
    { dedupe: "defer" }
  );

  if (import.meta.client) {
    watch(localeCode, async (newLocale) => {
      store.$reset();
      await store.fetchMenus(newLocale);
    });
  }

  return {
    menuData: computed(() => asyncData.data.value ?? store.menuData),
    ...asyncData,
  };
};

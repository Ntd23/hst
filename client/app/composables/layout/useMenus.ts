/**
 * Menu composable backed by Pinia + useAsyncData for SSR.
 */
export const useMenus = () => {
  const store = useMenuStore();
  const { localeCode } = useI18nText();
  const { menuReady } = useAppBoot();

  const asyncData = useAsyncData(
    `menus-${localeCode.value}`,
    () => store.fetchMenus(localeCode.value),
    { dedupe: "defer" }
  );

  if (import.meta.client) {
    watch(localeCode, async (newLocale) => {
      menuReady.value = false;
      store.$reset();
      await store.fetchMenus(newLocale);
    });
  }

  watchEffect(() => {
    menuReady.value = !asyncData.pending.value && Boolean(asyncData.data.value ?? store.menuData);
  });

  return {
    menuData: computed(() => asyncData.data.value ?? store.menuData),
    ...asyncData,
  };
};

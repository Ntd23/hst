export const useAppBoot = () => {
  const menuReady = useState<boolean>("app-boot-menu-ready", () => false);
  const layoutReady = useState<boolean>("app-boot-layout-ready", () => false);
  const pageReady = useState<boolean>("app-boot-page-ready", () => false);

  const isBootReady = computed(
    () => menuReady.value && layoutReady.value && pageReady.value
  );

  return {
    menuReady,
    layoutReady,
    pageReady,
    isBootReady,
  };
};

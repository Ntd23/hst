export const useServicesShortcode = (sourceData: MaybeRefOrGetter<any>) => {
  const rootData = computed(() => {
    const data = toValue(sourceData);

    return data?.content || data || {};
  });

  const sectionData = computed(
    () => rootData.value?.shortcode || rootData.value?.data || rootData.value || {}
  );
  const services = computed(() => rootData.value?.services || rootData.value?.items || []);
  const imageLoading = computed(() =>
    sectionData.value?.enable_lazy_loading === "yes" ? "lazy" : "eager"
  );
  const isMobile = ref(true);
  let mediaQuery: MediaQueryList | null = null;
  let mediaQueryListener: ((event: MediaQueryListEvent) => void) | null = null;

  const syncBreakpoint = () => {
    if (!import.meta.client) {
      return;
    }

    mediaQuery = window.matchMedia("(min-width: 1024px)");
    isMobile.value = !mediaQuery.matches;

    mediaQueryListener = (event: MediaQueryListEvent) => {
      isMobile.value = !event.matches;
    };

    mediaQuery.addEventListener("change", mediaQueryListener);
  };

  const cleanupBreakpoint = () => {
    if (mediaQuery && mediaQueryListener) {
      mediaQuery.removeEventListener("change", mediaQueryListener);
    }

    mediaQuery = null;
    mediaQueryListener = null;
  };

  const featuredInitial = { opacity: 0, scale: 0.93, y: 16, filter: "blur(6px)" };
  const sliderInitial = (index: number) =>
    isMobile.value ? { opacity: 0, x: 60 + index * 20 } : { opacity: 0, y: 50 };

  onMounted(() => {
    syncBreakpoint();
  });

  onBeforeUnmount(() => {
    cleanupBreakpoint();
  });

  return {
    sectionData,
    services,
    imageLoading,
    featuredInitial,
    sliderInitial,
  };
};

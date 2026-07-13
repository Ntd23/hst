export const useServicesTabShortcode = (
  sourceData: MaybeRefOrGetter<any>
) => {
  const rootData = computed(() => {
    const data = toValue(sourceData);

    return data?.content || data || {};
  });

  const sectionData = computed(
    () => rootData.value?.shortcode || rootData.value?.data || rootData.value || {}
  );
  const tabs = computed(() =>
    Array.isArray(rootData.value?.tabs) ? rootData.value.tabs : []
  );
  const activeIndex = ref(0);
  const activeTab = computed(() => tabs.value[activeIndex.value] || null);
  const imageLoading = computed(() =>
    sectionData.value?.enable_lazy_loading === "yes" ? "lazy" : "eager"
  );

  watch(
    tabs,
    (items) => {
      if (activeIndex.value >= items.length) {
        activeIndex.value = 0;
      }
    },
    { immediate: true }
  );

  const setActiveTab = (index: number) => {
    if (index >= 0 && index < tabs.value.length) {
      activeIndex.value = index;
    }
  };

  const tabUrl = (tab: any) => {
    const customUrl = tab?.button_url;
    const serviceUrl = tab?.service?.url;

    if (customUrl && customUrl !== serviceUrl) {
      return customUrl;
    }

    return tab?.service?.slug ? `/services/${tab.service.slug}` : customUrl || "#";
  };

  return {
    sectionData,
    tabs,
    activeIndex,
    activeTab,
    imageLoading,
    setActiveTab,
    tabUrl,
  };
};

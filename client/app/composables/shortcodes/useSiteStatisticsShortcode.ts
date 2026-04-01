export const useSiteStatisticsShortcode = (sourceData: MaybeRefOrGetter<any>) => {
  const sectionData = computed(() => {
    const data = toValue(sourceData);
    const rootData = data?.content || data || {};

    return rootData?.data || rootData || {};
  });

  const tabs = computed<any[]>(
    () => sectionData.value?.tabs || sectionData.value?.items || []
  );

  const statisticValues = computed(() =>
    tabs.value.map((tab) => Number.parseInt(tab?.data, 10) || 0)
  );

  return {
    sectionData,
    tabs,
    statisticValues,
  };
};

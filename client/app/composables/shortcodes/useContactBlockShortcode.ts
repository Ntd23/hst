export const useContactBlockShortcode = (sourceData: MaybeRefOrGetter<any>) => {
  const rootData = computed(() => {
    const data = toValue(sourceData);

    return data?.content || data || {};
  });

  const sectionData = computed(() => rootData.value?.data || rootData.value || {});
  const imageLoading = computed(() =>
    sectionData.value?.enable_lazy_loading === "yes" ? "lazy" : "eager"
  );

  return {
    sectionData,
    imageLoading,
  };
};

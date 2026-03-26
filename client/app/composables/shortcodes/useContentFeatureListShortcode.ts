export const useContentFeatureListShortcode = (
  sourceData: MaybeRefOrGetter<any>
) => {
  const rootData = computed(() => {
    const data = toValue(sourceData);

    return data?.content || data || {};
  });

  const sectionData = computed(
    () => rootData.value?.shortcode || rootData.value?.data || rootData.value || {}
  );
  const features = computed(() => rootData.value?.features || []);
  const hasHeader = computed(
    () => Boolean(sectionData.value?.title || sectionData.value?.description)
  );

  return {
    sectionData,
    features,
    hasHeader,
  };
};

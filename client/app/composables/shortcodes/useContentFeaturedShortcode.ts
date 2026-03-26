export const useContentFeaturedShortcode = (
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

  return {
    sectionData,
    features,
  };
};

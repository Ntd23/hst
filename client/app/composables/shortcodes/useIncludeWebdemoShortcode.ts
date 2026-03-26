export const useIncludeWebdemoShortcode = (
  sourceData: MaybeRefOrGetter<any>
) => {
  const sectionData = computed(() => toValue(sourceData) || {});
  const products = computed(() => sectionData.value?.items || []);

  return {
    sectionData,
    products,
  };
};

export const usePostsBlogShortcode = (sourceData: MaybeRefOrGetter<any>) => {
  const sectionData = computed(() => toValue(sourceData) || {});
  const posts = computed(() => sectionData.value?.items || []);

  return {
    sectionData,
    posts,
  };
};

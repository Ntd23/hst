export const useWebDemosShortcode = (sourceData: MaybeRefOrGetter<any>) => {
  const sectionData = computed(() => toValue(sourceData) || {});
  const route = useRoute();
  const webs = computed(() => sectionData.value?.items || []);

  return {
    sectionData,
    route,
    webs,
  };
};

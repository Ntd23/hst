export const usePostsBlogShortcode = (sourceData: MaybeRefOrGetter<any>) => {
  const sectionData = computed(() => toValue(sourceData) || {});
  const posts = computed(() => sectionData.value?.items || []);
  const ready = ref(false);

  onMounted(() => {
    requestAnimationFrame(() => {
      ready.value = true;
    });
  });

  return {
    sectionData,
    posts,
    ready,
  };
};

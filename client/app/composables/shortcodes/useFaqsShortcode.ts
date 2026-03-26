export const useFaqsShortcode = (sourceData: MaybeRefOrGetter<any>) => {
  const sectionData = computed(() => {
    const data = toValue(sourceData);

    return data?.content || data || {};
  });

  const faqs = computed(() => sectionData.value?.items || []);
  const activeFaq = ref<any | null>(null);

  const setActiveFaq = (faq: any) => {
    activeFaq.value = faq;
  };

  watch(
    faqs,
    (items) => {
      if (!items.length) {
        activeFaq.value = null;
        return;
      }

      const currentId = activeFaq.value?.id;
      const matchedFaq = items.find((item: any) => item?.id === currentId);
      activeFaq.value = matchedFaq || items[0];
    },
    { immediate: true }
  );

  return {
    sectionData,
    faqs,
    activeFaq,
    setActiveFaq,
  };
};

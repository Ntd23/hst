export const useTestimonialsShortcode = (sourceData: MaybeRefOrGetter<any>) => {
  const sectionData = computed(() => {
    const data = toValue(sourceData);
    const rootData = data?.content || data || {};

    return rootData?.data || rootData || {};
  });

  const items = computed(() => sectionData.value?.items || []);
  const sliderRef = ref<HTMLElement | null>(null);
  const activeIndex = ref(0);
  const autoplayDelay = 6000;
  let autoplayInterval: ReturnType<typeof setInterval> | null = null;

  const clearAutoplay = () => {
    if (autoplayInterval) {
      clearInterval(autoplayInterval);
      autoplayInterval = null;
    }
  };

  const updateActiveIndex = () => {
    const slider = sliderRef.value;
    if (!slider) {
      return;
    }

    const firstChild = slider.children[0] as HTMLElement | undefined;
    if (!firstChild) {
      activeIndex.value = 0;
      return;
    }

    const itemWidth = firstChild.clientWidth + 24;
    activeIndex.value = Math.round(slider.scrollLeft / itemWidth);
  };

  const scrollToIndex = (index: number) => {
    const slider = sliderRef.value;
    if (!slider || !items.value.length) {
      return;
    }

    const normalizedIndex = ((index % items.value.length) + items.value.length) % items.value.length;
    const item = slider.children[normalizedIndex] as HTMLElement | undefined;

    if (!item) {
      return;
    }

    const offset = item.offsetLeft - slider.clientWidth / 2 + item.clientWidth / 2;
    slider.scrollTo({ left: offset, behavior: "smooth" });
    activeIndex.value = normalizedIndex;
  };

  const scrollNext = () => {
    if (!items.value.length) {
      return;
    }

    scrollToIndex(activeIndex.value + 1);
  };

  const startAutoplay = () => {
    if (import.meta.server) {
      return;
    }

    clearAutoplay();

    if (items.value.length <= 1) {
      return;
    }

    autoplayInterval = setInterval(() => {
      scrollNext();
    }, autoplayDelay);
  };

  watch(
    items,
    (nextItems) => {
      if (!nextItems.length) {
        activeIndex.value = 0;
        clearAutoplay();
        return;
      }

      if (activeIndex.value >= nextItems.length) {
        activeIndex.value = 0;
      }

      nextTick(() => {
        updateActiveIndex();
      });
    },
    { immediate: true }
  );

  onMounted(() => {
    const slider = sliderRef.value;
    if (!slider) {
      return;
    }

    slider.addEventListener("scroll", updateActiveIndex);
    updateActiveIndex();
    startAutoplay();
  });

  onUnmounted(() => {
    sliderRef.value?.removeEventListener("scroll", updateActiveIndex);
    clearAutoplay();
  });

  return {
    sectionData,
    items,
    sliderRef,
    activeIndex,
    scrollToIndex,
  };
};

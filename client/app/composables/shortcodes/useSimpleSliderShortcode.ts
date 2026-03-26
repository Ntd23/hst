export const useSimpleSliderShortcode = (sourceData: MaybeRefOrGetter<any>) => {
  const slideInterval = 6000;
  const typeSpeed = 45;
  const fallbackTitle = "HISOTECH";

  const sectionData = computed(() => {
    const data = toValue(sourceData);

    return data?.content || data || {};
  });

  const sliderItems = computed(() => sectionData.value?.items ?? []);
  const activeSlide = ref(0);
  const typedText = ref("");
  const isTyping = ref(false);

  const currentItem = computed(
    () => sliderItems.value[activeSlide.value] ?? null
  );

  let slideTimer: ReturnType<typeof setInterval> | null = null;
  let typeTimer: ReturnType<typeof setTimeout> | null = null;
  let cursorTimer: ReturnType<typeof setTimeout> | null = null;

  const clearTimers = () => {
    if (slideTimer) {
      clearInterval(slideTimer);
      slideTimer = null;
    }

    if (typeTimer) {
      clearTimeout(typeTimer);
      typeTimer = null;
    }

    if (cursorTimer) {
      clearTimeout(cursorTimer);
      cursorTimer = null;
    }
  };

  const typeTitle = (title?: string | null) => {
    const text = title || fallbackTitle;

    if (import.meta.server) {
      typedText.value = text;
      isTyping.value = false;
      return;
    }

    if (typeTimer) {
      clearTimeout(typeTimer);
      typeTimer = null;
    }

    if (cursorTimer) {
      clearTimeout(cursorTimer);
      cursorTimer = null;
    }

    typedText.value = "";
    isTyping.value = true;

    let index = 0;

    const tick = () => {
      if (index < text.length) {
        typedText.value = text.slice(0, index + 1);
        index += 1;
        typeTimer = setTimeout(tick, typeSpeed);
        return;
      }

      cursorTimer = setTimeout(() => {
        isTyping.value = false;
      }, 800);
    };

    tick();
  };

  const nextSlide = () => {
    if (sliderItems.value.length <= 1) {
      return;
    }

    activeSlide.value = (activeSlide.value + 1) % sliderItems.value.length;
  };

  const resetSlideTimer = () => {
    if (import.meta.server) {
      return;
    }

    if (slideTimer) {
      clearInterval(slideTimer);
      slideTimer = null;
    }

    if (sliderItems.value.length <= 1) {
      return;
    }

    slideTimer = setInterval(nextSlide, slideInterval);
  };

  const goToSlide = (index: number) => {
    if (index < 0 || index >= sliderItems.value.length) {
      return;
    }

    activeSlide.value = index;
    resetSlideTimer();
  };

  watch(
    sliderItems,
    (items) => {
      if (!items.length) {
        activeSlide.value = 0;
        typedText.value = fallbackTitle;
        isTyping.value = false;
        clearTimers();
        return;
      }

      if (activeSlide.value >= items.length) {
        activeSlide.value = 0;
      }
    },
    { immediate: true }
  );

  watch(
    () => currentItem.value?.title,
    (title) => {
      typeTitle(title);
    },
    { immediate: true }
  );

  onBeforeUnmount(() => {
    clearTimers();
  });

  onMounted(() => {
    resetSlideTimer();
  });

  return {
    sectionData,
    sliderItems,
    currentItem,
    activeSlide,
    typedText,
    isTyping,
    slideInterval,
    goToSlide,
    nextSlide,
    resetSlideTimer,
  };
};

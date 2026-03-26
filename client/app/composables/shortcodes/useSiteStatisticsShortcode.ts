export const useSiteStatisticsShortcode = (sourceData: MaybeRefOrGetter<any>) => {
  const sectionData = computed(() => {
    const data = toValue(sourceData);
    const rootData = data?.content || data || {};

    return rootData?.data || rootData || {};
  });

  const tabs = computed<any[]>(() => sectionData.value?.tabs || sectionData.value?.items || []);
  const sectionRef = ref<HTMLElement | null>(null);
  const animatedValues = ref<number[]>([]);
  const hasAnimated = ref(false);
  const countTimers = new Set<ReturnType<typeof setInterval>>();
  let observer: IntersectionObserver | null = null;

  const clearCountTimers = () => {
    countTimers.forEach((timer) => clearInterval(timer));
    countTimers.clear();
  };

  const resetAnimatedValues = () => {
    animatedValues.value = tabs.value.map(() => 0);
    hasAnimated.value = false;
    clearCountTimers();
  };

  const animateCountUp = () => {
    if (import.meta.server) {
      animatedValues.value = tabs.value.map(
        (tab) => Number.parseInt(tab?.data, 10) || 0
      );
      hasAnimated.value = true;
      return;
    }

    if (hasAnimated.value) {
      return;
    }

    hasAnimated.value = true;

    tabs.value.forEach((tab, index) => {
      const target = Number.parseInt(tab?.data, 10) || 0;
      const duration = 2000;
      const steps = 60;
      const stepDuration = duration / steps;
      const increment = target / steps;
      let current = 0;

      const timer = setInterval(() => {
        current += increment;

        if (current >= target) {
          current = target;
          clearInterval(timer);
          countTimers.delete(timer);
        }

        animatedValues.value[index] = Math.round(current);
      }, stepDuration);

      countTimers.add(timer);
    });
  };

  const observeSection = () => {
    if (import.meta.server) {
      return;
    }

    if (!sectionRef.value) {
      return;
    }

    observer?.disconnect();
    observer = new IntersectionObserver(
      (entries) => {
        if (entries[0]?.isIntersecting) {
          animateCountUp();
          observer?.disconnect();
          observer = null;
        }
      },
      { threshold: 0.3 }
    );

    observer.observe(sectionRef.value);
  };

  watch(
    tabs,
    () => {
      resetAnimatedValues();
      nextTick(() => {
        observeSection();
      });
    },
    { immediate: true }
  );

  onMounted(() => {
    observeSection();
  });

  onBeforeUnmount(() => {
    observer?.disconnect();
    observer = null;
    clearCountTimers();
  });

  return {
    sectionData,
    tabs,
    sectionRef,
    animatedValues,
  };
};

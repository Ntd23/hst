export const useAboutUsInformationShortcode = (
  sourceData: MaybeRefOrGetter<any>
) => {
  const sectionData = computed(() => {
    const data = toValue(sourceData);
    const nestedData = data?.data || data || {};

    return {
      ...(data || {}),
      ...nestedData,
    };
  });

  const tabs = computed(() => sectionData.value?.tabs || []);

  const isImageRight = computed(() => {
    const style = String(sectionData.value?.style || "");

    return (
      style.includes("14") ||
      style.includes("right") ||
      style.includes("style-8")
    );
  });

  const isFloatingMode = computed(
    () => isImageRight.value || tabs.value.length <= 3
  );

  return {
    sectionData,
    tabs,
    isImageRight,
    isFloatingMode,
  };
};

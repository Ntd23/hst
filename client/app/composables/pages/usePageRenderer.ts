export const usePageRenderer = async (slug: MaybeRefOrGetter<string>) => {
  const resolvedSlug = computed(() => toValue(slug));
  const { Shortcodes, mapSectionsToShortcodes } = useMappedShortcodes();
  const { data: pageData, pending } = await usePageSections<any>(
    resolvedSlug.value
  );

  watchEffect(() => {
    const sections =
      pageData.value?.sections ?? pageData.value?.data?.sections ?? [];
    mapSectionsToShortcodes(sections);
  });

  const pageTitle = computed(() => {
    const name = pageData.value?.page?.name ?? pageData.value?.data?.page?.name;
    if (name) {
      return name;
    }

    return resolvedSlug.value
      .split("-")
      .map((word: string) => word.charAt(0).toUpperCase() + word.slice(1))
      .join(" ");
  });

  return {
    pageData,
    pending,
    Shortcodes,
    pageTitle,
  };
};

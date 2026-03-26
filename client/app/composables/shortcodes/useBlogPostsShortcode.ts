export const useBlogPostsShortcode = (sourceData: MaybeRefOrGetter<any>) => {
  const rootData = computed(() => {
    const data = toValue(sourceData);

    return data?.content || data || {};
  });

  const articles = computed(() => rootData.value?.items ?? []);
  const isListingMode = computed(() => articles.value.length > 4);

  const formatDate = (dateStr?: string) => {
    if (!dateStr) {
      return "";
    }

    try {
      return new Date(dateStr).toLocaleDateString("vi-VN", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
      });
    } catch {
      return dateStr;
    }
  };

  return {
    articles,
    isListingMode,
    formatDate,
  };
};

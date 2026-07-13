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

    const matched = String(dateStr).match(/(\d{4})-(\d{2})-(\d{2})/);
    if (matched) {
      const [, year, month, day] = matched;
      return `${day}/${month}/${year}`;
    }

    const parsed = new Date(dateStr);
    if (Number.isNaN(parsed.getTime())) {
      return dateStr;
    }

    return `${String(parsed.getDate()).padStart(2, "0")}/${String(
      parsed.getMonth() + 1
    ).padStart(2, "0")}/${parsed.getFullYear()}`;
  };

  return {
    articles,
    isListingMode,
    formatDate,
  };
};

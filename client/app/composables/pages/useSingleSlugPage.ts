export const useSingleSlugPage = () => {
  const route = useRoute();
  const pageSlug = computed(() => String(route.params.page || ""));
  const isSingleSlug = computed(() => !route.params.detail);

  useEntitySeo(pageSlug.value);

  return {
    pageSlug,
    isSingleSlug,
  };
};

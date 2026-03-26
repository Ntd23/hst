export const useWebsiteDemoDetailPage = async (
  slug: MaybeRefOrGetter<string>
) => {
  const resolvedSlug = computed(() => toValue(slug));
  const route = useRoute();
  const { formatDate } = useCommonCardText();
  const { data: pageData, pending } = await usePageDetail<any>(
    resolvedSlug.value
  );

  const dataWeb = computed(() => ({
    name: pageData.value?.name,
    content: pageData.value?.content,
    image: pageData.value?.img_feautrer,
    url_admin: pageData.value?.url_admin,
    url_client: pageData.value?.url_client,
    seo_description: pageData.value?.seo_description,
    date: pageData.value?.date,
    formatted_date: formatDate(pageData.value?.date || pageData.value?.published_at),
  }));

  return {
    route,
    pageData,
    pending,
    dataWeb,
  };
};

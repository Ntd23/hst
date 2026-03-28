export const useBlogDetailPage = async (slug: MaybeRefOrGetter<string>) => {
  const resolvedSlug = computed(() => toValue(slug));
  const { formatDate } = useCommonCardText();
  const { resolveSidebarTypeFromPage } = useSidebarType();
  const { sidebarWidgetData } = useSidebarWidgets(
    resolveSidebarTypeFromPage("blog")
  );
  const { data: pageData, pending } = await usePageDetail<any>(
    resolvedSlug.value
  );

  const post = computed(() => ({
    name: pageData.value?.name,
    image: pageData.value?.image,
    content: pageData.value?.content,
    published_at: pageData.value?.published_at,
    formatted_published_at: formatDate(pageData.value?.published_at),
  }));

  const recentPosts = computed(() =>
    (pageData.value?.posts ?? []).map((item: any) => ({
      ...item,
      formatted_published_at: formatDate(item?.published_at || item?.created_at),
    }))
  );
  const categories = computed(() => pageData.value?.categories ?? []);
  const tags = computed(() => pageData.value?.tags ?? []);
  const sidebarWidgets = computed(() => sidebarWidgetData.value?.items ?? []);

  return {
    pageData,
    pending,
    post,
    recentPosts,
    categories,
    tags,
    sidebarWidgets,
  };
};

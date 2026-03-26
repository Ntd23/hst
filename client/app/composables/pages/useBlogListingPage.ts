export const useBlogListingPage = async () => {
  useEntitySeo("blog");

  const route = useRoute();
  const router = useRouter();
  const { translate, localeCode } = useI18nText();
  const { formatDate } = useCommonCardText();

  const pageTitle = computed(() =>
    translate("blogListing.title", localeCode.value === "en" ? "News" : "Tin tức")
  );
  const limit = 8;

  const currentPage = ref(Number(route.query.page) || 1);
  const searchQuery = ref((route.query.q as string) || "");
  const selectedCategory = ref((route.query.category as string) || "");
  const selectedTag = ref((route.query.tag as string) || "");

  const { data: apiResponse, pending, error, refresh } = await useBlogListing<any>(
    computed(() => ({
      limit,
      page: currentPage.value,
      q: searchQuery.value || undefined,
      category: selectedCategory.value || undefined,
      tag: selectedTag.value || undefined,
    })),
    { watch: false }
  );

  const posts = computed(() => apiResponse.value?.data?.posts?.items ?? []);
  const pagination = computed(() => apiResponse.value?.data?.posts);
  const categories = computed(
    () => apiResponse.value?.data?.sidebar?.categories ?? []
  );
  const recentPosts = computed(
    () => apiResponse.value?.data?.sidebar?.recent_posts ?? []
  );
  const tags = computed(() => apiResponse.value?.data?.sidebar?.tags ?? []);

  const syncUrl = () => {
    router.replace({
      query: {
        ...route.query,
        page: currentPage.value > 1 ? currentPage.value : undefined,
        q: searchQuery.value || undefined,
        category: selectedCategory.value || undefined,
        tag: selectedTag.value || undefined,
      },
    });

    refresh();
  };

  const handleSearch = () => {
    currentPage.value = 1;
    syncUrl();
  };

  const toggleCategory = (slug: string) => {
    selectedCategory.value = selectedCategory.value === slug ? "" : slug;
    currentPage.value = 1;
    syncUrl();
  };

  const toggleTag = (slug: string) => {
    selectedTag.value = selectedTag.value === slug ? "" : slug;
    currentPage.value = 1;
    syncUrl();
  };

  watch(currentPage, () => {
    syncUrl();

    if (import.meta.client) {
      window.scrollTo({ top: 0, behavior: "smooth" });
    }
  });

  return {
    pageTitle,
    currentPage,
    searchQuery,
    selectedCategory,
    selectedTag,
    pending,
    error,
    posts,
    pagination,
    categories,
    recentPosts,
    tags,
    handleSearch,
    toggleCategory,
    toggleTag,
    formatDate,
  };
};

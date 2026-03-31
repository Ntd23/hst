import { useDebounceFn } from "@vueuse/core";

export const useBlogListingPage = async () => {
  useEntitySeo("blog");

  const route = useRoute();
  const router = useRouter();
  const { translate, localeCode } = useI18nText();
  const { formatDate } = useCommonCardText();
  const { resolveSidebarTypeFromPage } = useSidebarType();
  const { sidebarWidgetData } = useSidebarWidgets(
    resolveSidebarTypeFromPage("blog")
  );

  const pageTitle = computed(() =>
    translate("blogListing.title", localeCode.value === "en" ? "News" : "Tin tức")
  );
  const limit = 8;

  const currentPage = ref(Number(route.query.page) || 1);
  const searchQuery = ref((route.query.q as string) || "");
  const selectedCategory = ref((route.query.category as string) || "");
  const selectedTag = ref((route.query.tag as string) || "");

  const buildQuerySignature = (input: {
    page?: string | number;
    q?: string;
    category?: string;
    tag?: string;
  }) =>
    JSON.stringify({
      page: Number(input.page) || 1,
      q: input.q || "",
      category: input.category || "",
      tag: input.tag || "",
    });

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
  const sidebarWidgets = computed(() => sidebarWidgetData.value?.items ?? []);
  const refetching = ref(false);
  const loading = computed(() => pending.value || refetching.value);
  const syncingFromRoute = ref(false);

  const routeQuerySignature = computed(() =>
    buildQuerySignature({
      page: route.query.page as string | number | undefined,
      q: route.query.q as string | undefined,
      category: route.query.category as string | undefined,
      tag: route.query.tag as string | undefined,
    })
  );

  const stateQuerySignature = computed(() =>
    buildQuerySignature({
      page: currentPage.value,
      q: searchQuery.value,
      category: selectedCategory.value,
      tag: selectedTag.value,
    })
  );

  const syncUrl = async () => {
    refetching.value = true;

    await router.replace({
      query: {
        ...route.query,
        page: currentPage.value > 1 ? currentPage.value : undefined,
        q: searchQuery.value || undefined,
        category: selectedCategory.value || undefined,
        tag: selectedTag.value || undefined,
      },
    });

    try {
      await refresh();
    } finally {
      refetching.value = false;
    }
  };

  const handlePageChange = async (page: number) => {
    if (page === currentPage.value) {
      return;
    }

    currentPage.value = page;
    await syncUrl();

    if (import.meta.client) {
      window.scrollTo({ top: 0, behavior: "smooth" });
    }
  };

  const handleSearch = async () => {
    currentPage.value = 1;
    await syncUrl();
  };

  const handleDebouncedSearch = useDebounceFn(async () => {
    currentPage.value = 1;
    await syncUrl();
  }, 2000);

  const toggleCategory = async (slug: string) => {
    selectedCategory.value = selectedCategory.value === slug ? "" : slug;
    currentPage.value = 1;
    await syncUrl();
  };

  const toggleTag = async (slug: string) => {
    selectedTag.value = selectedTag.value === slug ? "" : slug;
    currentPage.value = 1;
    await syncUrl();
  };

  watch(routeQuerySignature, async (signature) => {
    if (signature === stateQuerySignature.value) {
      return;
    }

    syncingFromRoute.value = true;
    currentPage.value = Number(route.query.page) || 1;
    searchQuery.value = (route.query.q as string) || "";
    selectedCategory.value = (route.query.category as string) || "";
    selectedTag.value = (route.query.tag as string) || "";

    refetching.value = true;

    try {
      await refresh();
    } finally {
      refetching.value = false;
      syncingFromRoute.value = false;
    }
  });

  watch(searchQuery, (value) => {
    if (syncingFromRoute.value) {
      return;
    }

    const normalizedValue = value.trim();
    const currentQuery =
      typeof route.query.q === "string" ? route.query.q.trim() : "";

    if (normalizedValue === currentQuery) {
      return;
    }

    handleDebouncedSearch();
  });

  return {
    pageTitle,
    currentPage,
    loading,
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
    sidebarWidgets,
    handlePageChange,
    handleSearch,
    toggleCategory,
    toggleTag,
    formatDate,
  };
};

export const useServiceDetailPage = (slug: MaybeRefOrGetter<string>) => {
  const resolvedSlug = computed(() => toValue(slug));
  const { formatDate } = useCommonCardText();
  const { Shortcodes, mapSectionsToShortcodes } = useMappedShortcodes();
  const { resolveSidebarTypeFromPage } = useSidebarType();
  const { sidebarWidgetData } = useSidebarWidgets(
    resolveSidebarTypeFromPage("service")
  );
  const { data: pageData, pending } = usePageDetail<any>(
    resolvedSlug.value
  );
  const { data: servicesPage } = usePageSections<any>("services");
  const { data: blogListing } = useBlogListing<any>(
    computed(() => ({
      limit: 3,
    }))
  );

  watch(
    () => pageData.value?.sections,
    (sections) => {
      const filteredSections = (sections || []).filter((section: any) => {
        const shortcode = String(section?.shortcode || "");

        return shortcode !== "services";
      });

      mapSectionsToShortcodes(filteredSections);
    },
    { immediate: true }
  );

  const sidebarServices = computed(() => {
    const sections = servicesPage.value?.sections || [];
    const servicesSection = sections.find(
      (section: any) => section.shortcode === "services"
    );
    const services = servicesSection?.content?.services || [];

    return services.filter((service: any) => service?.slug !== resolvedSlug.value);
  });

  const recentPosts = computed(() =>
    (blogListing.value?.data?.sidebar?.recent_posts ?? []).map((post: any) => ({
      ...post,
      formatted_published_at: formatDate(post?.published_at || post?.created_at),
    }))
  );

  const handbookItems = ref([
    {
      label: "document.pdf",
      icon: "i-lucide-file-text",
      color: "text-red-500",
      url: "#",
    },
    {
      label: "document.docx",
      icon: "i-lucide-file-text",
      color: "text-blue-500",
      url: "#",
    },
  ]);

  const cleanContent = computed(() => {
    const content = pageData.value?.content;
    if (!content) {
      return null;
    }

    const stripped = content
      .replace(/<[^>]*>/g, "")
      .replace(/&nbsp;|&#160;/gi, " ")
      .replace(/\u00a0/g, " ")
      .trim();

    return stripped ? content : null;
  });

  const sidebarWidgets = computed(() => sidebarWidgetData.value?.items ?? []);

  return {
    pageData,
    pending,
    Shortcodes,
    sidebarServices,
    recentPosts,
    handbookItems,
    cleanContent,
    sidebarWidgets,
  };
};

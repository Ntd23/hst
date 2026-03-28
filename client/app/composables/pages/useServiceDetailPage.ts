export const useServiceDetailPage = async (slug: MaybeRefOrGetter<string>) => {
  const resolvedSlug = computed(() => toValue(slug));
  const { formatDate } = useCommonCardText();
  const { Shortcodes, mapSectionsToShortcodes } = useMappedShortcodes();
  const { sidebarWidgetData } = useSidebarWidgets("service");
  const { data: pageData, pending } = await usePageDetail<any>(
    resolvedSlug.value
  );
  const { data: servicesPage } = await usePageSections<any>("services");
  const { data: blogListing } = await useBlogListing<any>(
    computed(() => ({
      limit: 3,
    }))
  );

  watch(
    () => pageData.value?.sections,
    (sections) => {
      const filteredSections = (sections || []).filter(
        (section: any) => {
          const shortcode = String(section?.shortcode || "");

          return !shortcode.startsWith("services");
        }
      );

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

export const useBlogDetailShare = (options: {
  post: MaybeRefOrGetter<{ name?: string; image?: string } | null | undefined>;
}) => {
  const { translate, localeCode } = useI18nText();
  const { siteUrl, canonicalUrl } = useSeoContext();

  const toAbsoluteUrl = (value?: string) => {
    if (!value) {
      return undefined;
    }

    if (/^https?:\/\//i.test(value)) {
      return value;
    }

    return `${siteUrl.value}${value.startsWith("/") ? value : `/${value}`}`;
  };

  const labels = computed(() => ({
    share: translate("blogDetail.share", localeCode.value === "en" ? "Share" : "Chia sẻ"),
    facebook: translate(
      "blogDetail.shareFacebook",
      localeCode.value === "en" ? "Share on Facebook" : "Chia sẻ lên Facebook"
    ),
    x: translate(
      "blogDetail.shareX",
      localeCode.value === "en" ? "Share on X" : "Chia sẻ lên X"
    ),
    linkedin: translate(
      "blogDetail.shareLinkedIn",
      localeCode.value === "en" ? "Share on LinkedIn" : "Chia sẻ lên LinkedIn"
    ),
    pinterest: translate(
      "blogDetail.sharePinterest",
      localeCode.value === "en" ? "Share on Pinterest" : "Chia sẻ lên Pinterest"
    ),
  }));

  const shareLinks = computed(() => {
    const currentPost = toValue(options.post);
    const url = encodeURIComponent(canonicalUrl.value);
    const title = encodeURIComponent(currentPost?.name || "");
    const media = encodeURIComponent(toAbsoluteUrl(currentPost?.image) || "");

    return [
      {
        name: "facebook",
        label: labels.value.facebook,
        shortLabel: "f",
        textClass: "text-sm font-bold",
        className: "hover:bg-[#1877F2] hover:text-white",
        href: `https://www.facebook.com/sharer/sharer.php?u=${url}`,
      },
      {
        name: "x",
        label: labels.value.x,
        shortLabel: "X",
        textClass: "text-sm font-bold",
        className: "hover:bg-black hover:text-white",
        href: `https://twitter.com/intent/tweet?url=${url}&text=${title}`,
      },
      {
        name: "linkedin",
        label: labels.value.linkedin,
        shortLabel: "in",
        textClass: "text-xs font-bold",
        className: "hover:bg-[#0077B5] hover:text-white",
        href: `https://www.linkedin.com/sharing/share-offsite/?url=${url}`,
      },
      {
        name: "pinterest",
        label: labels.value.pinterest,
        shortLabel: "P",
        textClass: "text-sm font-bold",
        className: "hover:bg-[#E60023] hover:text-white",
        href: `https://pinterest.com/pin/create/button/?url=${url}&description=${title}&media=${media}`,
      },
    ];
  });

  return {
    siteUrl,
    canonicalUrl,
    labels,
    shareLinks,
    toAbsoluteUrl,
  };
};

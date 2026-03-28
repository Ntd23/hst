export const useCommonCardText = () => {
  const { translate, localeCode } = useI18nText();

  const blogReadMoreLabel = computed(() =>
    translate("news.readMore", localeCode.value === "en" ? "Read More" : "Đọc tiếp")
  );

  const detailLabel = computed(() =>
    localeCode.value === "en" ? "View Details" : "Chi tiết"
  );

  const byLabel = computed(() =>
    localeCode.value === "en" ? "By" : "Bởi"
  );

  const formatDate = (dateStr?: string) => {
    if (!dateStr) {
      return "";
    }

    const parsed = new Date(dateStr);

    if (Number.isNaN(parsed.getTime())) {
      return dateStr;
    }

    return parsed.toLocaleDateString(
      localeCode.value === "en" ? "en-US" : "vi-VN",
      {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
      }
    );
  };

  return {
    blogReadMoreLabel,
    detailLabel,
    byLabel,
    formatDate,
  };
};

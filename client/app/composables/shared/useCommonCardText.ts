export const useCommonCardText = () => {
  const { translate, localeCode } = useI18nText();

  const parseDateParts = (dateStr?: string) => {
    if (!dateStr) {
      return null;
    }

    const matched = String(dateStr).match(/(\d{4})-(\d{2})-(\d{2})/);
    if (matched) {
      const [, year, month, day] = matched;
      return { year, month, day };
    }

    const parsed = new Date(dateStr);
    if (Number.isNaN(parsed.getTime())) {
      return null;
    }

    return {
      year: String(parsed.getFullYear()),
      month: String(parsed.getMonth() + 1).padStart(2, "0"),
      day: String(parsed.getDate()).padStart(2, "0"),
    };
  };

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

    const parts = parseDateParts(dateStr);
    if (!parts) {
      return dateStr;
    }

    if (localeCode.value === "en") {
      return `${parts.month}/${parts.day}/${parts.year}`;
    }

    return `${parts.day}/${parts.month}/${parts.year}`;
  };

  return {
    blogReadMoreLabel,
    detailLabel,
    byLabel,
    formatDate,
  };
};

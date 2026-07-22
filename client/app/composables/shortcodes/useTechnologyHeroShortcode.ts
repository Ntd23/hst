import { computed, toValue, type MaybeRefOrGetter } from "vue";

type TechnologyHeroData = {
  badge?: string | null;
  title?: string | null;
  highlight_text?: string | null;
  description?: string | null;
  primary_button?: string | null;
  primary_url?: string | null;
  secondary_button?: string | null;
  secondary_url?: string | null;
  capability_1?: string | null;
  capability_2?: string | null;
  primary_color?: string | null;
  glow_color?: string | null;
  enable_3d?: string | number | boolean | null;
  poster?: string | null;
};

const TECHNOLOGY_HERO_DEFAULTS = {
  badge: "Giải pháp công nghệ toàn diện",
  title: "Kiến tạo giải pháp",
  highlight_text: "công nghệ đột phá",
  description:
    "Chúng tôi thiết kế và phát triển các nền tảng số giúp doanh nghiệp vận hành hiệu quả, tối ưu trải nghiệm và sẵn sàng tăng trưởng.",
  primary_button: "Khám phá giải pháp",
  primary_url: "/giai-phap",
  secondary_button: "Xem dự án",
  secondary_url: "/du-an",
  capability_1: "Thiết kế UI/UX",
  capability_2: "Phát triển website",
  primary_color: "#0866FF",
  glow_color: "#35D6FF",
} as const;

const normalizeHexColor = (value: unknown, fallback: string) => {
  const color = String(value || "").trim();

  return /^#(?:[\da-f]{3}|[\da-f]{6})$/i.test(color) ? color : fallback;
};

const isEnabled = (value: TechnologyHeroData["enable_3d"]) => {
  const normalized = typeof value === "string" ? value.toLowerCase() : value;

  return !(
    normalized === false ||
    normalized === 0 ||
    normalized === "0" ||
    normalized === "no" ||
    normalized === "false" ||
    normalized === "off"
  );
};

export const useTechnologyHeroShortcode = (
  sourceData: MaybeRefOrGetter<any>
) => {
  const rootData = computed(() => {
    const data = toValue(sourceData);

    return data?.content || data || {};
  });

  const rawData = computed<TechnologyHeroData>(
    () => rootData.value?.data || rootData.value || {}
  );

  const sectionData = computed(() => ({
    ...TECHNOLOGY_HERO_DEFAULTS,
    ...rawData.value,
    primary_color: normalizeHexColor(
      rawData.value.primary_color,
      TECHNOLOGY_HERO_DEFAULTS.primary_color
    ),
    glow_color: normalizeHexColor(
      rawData.value.glow_color,
      TECHNOLOGY_HERO_DEFAULTS.glow_color
    ),
  }));

  const capabilities = computed(() =>
    [
      sectionData.value.capability_1,
      sectionData.value.capability_2,
    ].filter((item): item is string => Boolean(item?.trim()))
  );

  const enable3d = computed(() => isEnabled(rawData.value.enable_3d ?? "yes"));

  const heroStyle = computed(() => ({
    "--tech-hero-primary": sectionData.value.primary_color,
    "--tech-hero-glow": sectionData.value.glow_color,
  }));

  return {
    sectionData,
    capabilities,
    enable3d,
    heroStyle,
  };
};

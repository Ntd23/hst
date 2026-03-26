import { computed } from "vue";
import type { AppLocale } from "~~/shared/i18n/types";

export type PageSeoInput = {
  title: string;
  description: string;
  image?: string;
  type?: "website" | "article";
  robots?: string;
  favicon?: string;
};

type SeoFallbacks = {
  title: string;
  description: string;
  type?: "website" | "article";
  robots?: string;
};

type SeoPayload = Partial<{
  title: string;
  description: string;
  image: string;
  type: "website" | "article" | string;
  robots: string;
  favicon: string;
  seo_title: string;
  seo_description: string;
  og_image: string;
  seo_index: boolean;
  og: {
    image?: string;
    type?: "website" | "article" | string;
  };
}>;

export const toOgLocale = (locale: AppLocale) =>
  locale === "vi" ? "vi_VN" : "en_US";

export const resolveSeoImage = (image: string | undefined, siteUrl: string) => {
  if (!image) {
    return undefined;
  }

  if (/^https?:\/\//i.test(image)) {
    return image;
  }

  return `${siteUrl}${image.startsWith("/") ? image : `/${image}`}`;
};

export const createSeoInput = (
  payload: SeoPayload | null | undefined,
  fallbacks: SeoFallbacks
): PageSeoInput => {
  if (!payload) {
    return {
      title: fallbacks.title,
      description: fallbacks.description,
      type: fallbacks.type || "website",
      robots: fallbacks.robots || "index,follow",
    };
  }

  const resolvedType =
    payload.type === "blog"
      ? "article"
      : payload.type || payload.og?.type || fallbacks.type || "website";

  return {
    title: payload.seo_title || payload.title || fallbacks.title,
    description:
      payload.seo_description || payload.description || fallbacks.description,
    image: payload.og_image || payload.og?.image || payload.image || undefined,
    type: resolvedType === "article" ? "article" : "website",
    robots:
      typeof payload.seo_index === "boolean"
        ? payload.seo_index
          ? "index,follow"
          : "noindex,nofollow"
        : payload.robots || fallbacks.robots || "index,follow",
    favicon: payload.favicon || undefined,
  };
};

export const useSeoContext = () => {
  const runtimeConfig = useRuntimeConfig();
  const route = useRoute();
  const { localeCode } = useI18nText();

  const siteUrl = computed(() =>
    runtimeConfig.public.siteUrl.replace(/\/+$/, "")
  );
  const canonicalUrl = computed(() => `${siteUrl.value}${route.path || "/"}`);

  return {
    localeCode,
    siteUrl,
    canonicalUrl,
  };
};


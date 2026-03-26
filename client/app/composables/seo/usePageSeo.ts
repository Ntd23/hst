import { computed, toValue } from "vue";
import type { MaybeRefOrGetter } from "vue";
import {
  resolveSeoImage,
  toOgLocale,
  useSeoContext,
  type PageSeoInput,
} from "~/composables/seo/seo.helpers";

export const usePageSeo = (seo: MaybeRefOrGetter<PageSeoInput>) => {
  const { localeCode, siteUrl, canonicalUrl } = useSeoContext();
  const seoValue = computed(() => toValue(seo));

  const ogImage = computed(() =>
    resolveSeoImage(seoValue.value.image, siteUrl.value)
  );

  if (import.meta.server) {
    useSeoMeta({
      description: () => seoValue.value.description,
      robots: () => seoValue.value.robots || "index,follow",
      ogTitle: () => seoValue.value.title,
      ogDescription: () => seoValue.value.description,
      ogType: () => seoValue.value.type || "website",
      ogLocale: () => toOgLocale(localeCode.value),
      ogUrl: () => canonicalUrl.value,
      ogImage: () => ogImage.value,
      twitterCard: () => (ogImage.value ? "summary_large_image" : "summary"),
      twitterTitle: () => seoValue.value.title,
      twitterDescription: () => seoValue.value.description,
      twitterImage: () => ogImage.value,
    });
  }

  useSeoMeta({
    title: () => seoValue.value.title,
  });

  useHead(() => {
    const defaultCanonical = { rel: "canonical", href: canonicalUrl.value };
    const iconLink = seoValue.value.favicon
      ? { rel: "icon", href: seoValue.value.favicon }
      : undefined;

    return {
      link: [defaultCanonical, ...(iconLink ? [iconLink] : [])],
    };
  });
};

import { computed, toValue } from "vue";
import type { MaybeRefOrGetter } from "vue";
import {
  resolveSeoImage,
  toOgLocale,
  useSeoContext,
  type PageSeoInput,
} from "~/composables/seo/seo.helpers";

export const usePageSeo = (
  seo: MaybeRefOrGetter<PageSeoInput | null | undefined>
) => {
  const { localeCode, siteUrl, canonicalUrl } = useSeoContext();
  const seoValue = computed(() => toValue(seo));

  const ogImage = computed(() =>
    resolveSeoImage(seoValue.value?.image, siteUrl.value)
  );

  if (import.meta.server) {
    useSeoMeta({
      description: () => seoValue.value?.description,
      robots: () =>
        seoValue.value ? seoValue.value.robots || "index,follow" : undefined,
      ogTitle: () => seoValue.value?.title,
      ogDescription: () => seoValue.value?.description,
      ogType: () => (seoValue.value ? seoValue.value.type || "website" : undefined),
      ogLocale: () => (seoValue.value ? toOgLocale(localeCode.value) : undefined),
      ogUrl: () => (seoValue.value ? canonicalUrl.value : undefined),
      ogImage: () => ogImage.value,
      twitterCard: () =>
        seoValue.value ? (ogImage.value ? "summary_large_image" : "summary") : undefined,
      twitterTitle: () => seoValue.value?.title,
      twitterDescription: () => seoValue.value?.description,
      twitterImage: () => ogImage.value,
    });
  }

  useSeoMeta({
    title: () => seoValue.value?.title,
  });

  useHead(() => {
    if (!seoValue.value) {
      return {};
    }

    const defaultCanonical = { rel: "canonical", href: canonicalUrl.value };
    const iconLink = seoValue.value.favicon
      ? { rel: "icon", href: seoValue.value.favicon }
      : undefined;

    return {
      link: [defaultCanonical, ...(iconLink ? [iconLink] : [])],
    };
  });
};

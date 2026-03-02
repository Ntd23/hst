import { computed, toValue } from "vue";
import type { MaybeRefOrGetter } from "vue";

type PageSeoInput = {
  title: string;
  description: string;
  image?: string;
  type?: "website" | "article";
  robots?: string;
};

export type { PageSeoInput };

export const usePageSeo = (seo: MaybeRefOrGetter<PageSeoInput>) => {
  const runtimeConfig = useRuntimeConfig();
  const route = useRoute();
  const { locale } = useI18n();

  const siteUrl = computed(() => runtimeConfig.public.siteUrl.replace(/\/+$/, ""));
  const seoValue = computed(() => toValue(seo));
  const canonicalUrl = computed(() => `${siteUrl.value}${route.path || "/"}`);

  const ogImage = computed(() => {
    const image = seoValue.value.image;
    if (!image) return undefined;
    if (/^https?:\/\//i.test(image)) return image;
    return `${siteUrl.value}${image.startsWith("/") ? image : `/${image}`}`;
  });

  // Static SEO meta — chỉ cần trên server cho search engine bots
  if (import.meta.server) {
    useSeoMeta({
      description: () => seoValue.value.description,
      robots: () => seoValue.value.robots || 'index,follow',
      ogTitle: () => seoValue.value.title,
      ogDescription: () => seoValue.value.description,
      ogType: () => seoValue.value.type || 'website',
      ogLocale: () => locale.value === 'vi' ? 'vi_VN' : 'en_US',
      ogUrl: () => canonicalUrl.value,
      ogImage: () => ogImage.value,
      twitterCard: () => (ogImage.value ? 'summary_large_image' : 'summary'),
      twitterTitle: () => seoValue.value.title,
      twitterDescription: () => seoValue.value.description,
      twitterImage: () => ogImage.value,
    })
  }

  // Title — reactive vì user thấy trên tab browser
  useSeoMeta({
    title: () => seoValue.value.title,
  })

  useHead(() => ({
    link: [{ rel: 'canonical', href: canonicalUrl.value }],
  }));
};

import { computed, toValue } from "vue";
import type { MaybeRefOrGetter } from "vue";
import type { PageSeoInput } from "~/composables/usePageSeo";

type HomeSeoOverrides = Partial<PageSeoInput> | undefined;

const homeSeoDefaults: PageSeoInput = {
  title: "HISOTECH - Giải pháp chuyển đổi số cho doanh nghiệp",
  description:
    "HISOTECH cung cấp giải pháp phần mềm, dịch vụ công nghệ và tư vấn chuyển đổi số toàn diện cho doanh nghiệp Việt Nam.",
  type: "website",
  robots: "index,follow",
};

export const useHomeSeo = (overrides?: MaybeRefOrGetter<HomeSeoOverrides>) => {
  const seo = computed<PageSeoInput>(() => ({
    ...homeSeoDefaults,
    ...(toValue(overrides) || {}),
  }));

  usePageSeo(seo);
  return { seo };
};

import type { PageSeoInput } from '~/composables/usePageSeo'

const homeSeoDefaults: PageSeoInput = {
  title: 'HISOTECH - Giải pháp chuyển đổi số cho doanh nghiệp',
  description: 'HISOTECH cung cấp giải pháp phần mềm, dịch vụ công nghệ và tư vấn chuyển đổi số toàn diện cho doanh nghiệp Việt Nam.',
  type: 'website',
  robots: 'index,follow',
}

export const useHomeSeo = () => {
  const { locale } = useI18n()

  const { data } = useFetch<any>('/api/pages/home/meta', {
    key: `seo-home-${locale.value}`,
    query: computed(() => ({ locale: locale.value })),
  })

  const seo = computed<PageSeoInput>(() => {
    if (!data.value) return homeSeoDefaults
    return {
      title:       data.value.title       || homeSeoDefaults.title,
      description: data.value.description || homeSeoDefaults.description,
      image:       data.value.og?.image   || undefined,
      type:        data.value.og?.type    || 'website',
      robots:      data.value.robots      || 'index,follow',
      favicon:     data.value.favicon     || undefined,
    }
  })

  usePageSeo(seo)
  return { seo }
}

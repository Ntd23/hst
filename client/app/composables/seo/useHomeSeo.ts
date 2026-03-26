import {
  createSeoInput,
  type PageSeoInput,
} from '~/composables/seo/seo.helpers'

const homeSeoDefaults: PageSeoInput = {
  title: 'HISOTECH - Giải pháp chuyển đổi số cho doanh nghiệp',
  description: 'HISOTECH cung cấp giải pháp phần mềm, dịch vụ công nghệ và tư vấn chuyển đổi số toàn diện cho doanh nghiệp Việt Nam.',
  type: 'website',
  robots: 'index,follow',
}

export const useHomeSeo = () => {
  const { localeCode } = useI18nText()

  const { data } = useFetch<any>('/api/pages/homepage/meta', {
    key: `seo-home-${localeCode.value}`,
    query: computed(() => ({ locale: localeCode.value })),
  })

  const seo = computed(() => createSeoInput(data.value, homeSeoDefaults))

  usePageSeo(seo)
  return { seo }
}

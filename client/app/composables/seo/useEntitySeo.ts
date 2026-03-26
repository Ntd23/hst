import { createSeoInput } from '~/composables/seo/seo.helpers'

export const useEntitySeo = (slug: string, fallbackTitle?: string) => {
  const { localeCode } = useI18nText()

  const { data } = useFetch<any>(`/api/pages/${slug}/meta`, {
    key: `seo-${slug}-${localeCode.value}`,
    query: computed(() => ({ locale: localeCode.value })),
  })

  const seo = computed(() =>
    createSeoInput(data.value, {
      title: fallbackTitle || `${slug} | HISOTECH`,
      description: `${fallbackTitle || slug} page.`,
      type: 'website',
      robots: 'index,follow',
    })
  )

  usePageSeo(seo)
  return { seo }
}

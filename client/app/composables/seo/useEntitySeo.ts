import type { PageSeoInput } from '~/composables/seo/usePageSeo'

export const useEntitySeo = (slug: string, fallbackTitle?: string) => {
  const { locale } = useI18n()

  const { data } = useFetch<any>(`/api/pages/${slug}/meta`, {
    key: `seo-${slug}-${locale.value}`,
    query: computed(() => ({ locale: locale.value })),
  })

  const seo = computed<PageSeoInput>(() => {
    // Nếu API trả về null hoặc lỗi, dùng fallback
    if (!data.value) {
      return {
        title: fallbackTitle || `${slug} | HISOTECH`,
        description: `${fallbackTitle || slug} page.`,
        type: 'website',
        robots: 'index,follow',
      }
    }

    const res = data.value
    
    return {
      title:       res.seo_title       || fallbackTitle || `${slug} | HISOTECH`,
      description: res.seo_description || `${fallbackTitle || slug} page.`,
      image:       res.og_image        || undefined,
      type:        res.type === 'blog' ? 'article' : 'website',
      robots:      res.seo_index !== false ? 'index,follow' : 'noindex,nofollow',
      favicon:     res.favicon         || undefined,
    }
  })

  usePageSeo(seo)
  console.log('SEO data for slug:', seo.value)
  return { seo }
}

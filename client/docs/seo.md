
> Tài liệu hướng dẫn hệ thống SEO trong dự án. Cách thêm SEO cho trang mới, cấu trúc composables, và checklist kiểm tra.

---

## 🏗 Kiến trúc SEO

```
nuxt.config.ts          ← Meta mặc định (charset, viewport, favicon)
  ↓
usePageSeo()            ← Composable gốc: title, description, OG, Twitter, canonical
  ↓
useHomeSeo()            ← Composable trang chủ: kế thừa usePageSeo + defaults
```

**Luồng hoạt động:**
1. `nuxt.config.ts` set meta cơ bản (charset, viewport, lang)
2. Mỗi trang gọi composable SEO riêng (ví dụ: `useHomeSeo()`)
3. Composable gọi `usePageSeo()` → tự động tạo đầy đủ meta tags
4. Meta tĩnh (OG, Twitter, robots) chỉ render trên **server** → tối ưu performance

---

## 📄 File quan trọng

### `nuxt.config.ts` — Meta mặc định

```ts
app: {
  head: {
    htmlAttrs: { lang: 'vi' },
    title: 'HISOTECH - Giải pháp chuyển đổi số',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { name: 'robots', content: 'index,follow' },
    ],
    link: [{ rel: 'icon', href: '/favicon.ico' }],
  },
},
```

### `usePageSeo()` — Composable SEO gốc

**File:** `app/composables/usePageSeo.ts`

Nhận input và tự động tạo tất cả meta tags:

| Input | Bắt buộc | Mô tả |
|-------|----------|-------|
| `title` | ✅ | Tiêu đề trang (hiện trên tab browser + Google) |
| `description` | ✅ | Mô tả trang (hiện trên Google snippet) |
| `image` | ❌ | URL ảnh OG (hiện khi share Facebook/Zalo) |
| `type` | ❌ | `'website'` hoặc `'article'` (mặc định: `website`) |
| `robots` | ❌ | Directive cho bot (mặc định: `index,follow`) |

**Tự động tạo ra:**

| Meta tag | Giá trị |
|----------|---------|
| `<title>` | `title` (reactive — user thấy trên tab) |
| `description` | `description` |
| `robots` | `index,follow` |
| `og:title` | `title` |
| `og:description` | `description` |
| `og:type` | `website` / `article` |
| `og:locale` | `vi_VN` hoặc `en_US` (tự theo i18n) |
| `og:url` | Canonical URL |
| `og:image` | Ảnh OG (nếu có) |
| `twitter:card` | `summary_large_image` / `summary` |
| `twitter:title` | `title` |
| `twitter:description` | `description` |
| `twitter:image` | Ảnh OG (nếu có) |
| `<link rel="canonical">` | URL chuẩn của trang |

### `useHomeSeo()` — SEO trang chủ

**File:** `app/composables/seo/useHomeSeo.ts`

Kế thừa `usePageSeo()` với defaults cho trang chủ:

```ts
const homeSeoDefaults = {
  title: "HISOTECH - Giải pháp chuyển đổi số cho doanh nghiệp",
  description: "HISOTECH cung cấp giải pháp phần mềm...",
  type: "website",
  robots: "index,follow",
}
```

---

## 🚀 Cách thêm SEO cho trang mới

### Bước 1: Tạo composable SEO cho trang

```ts
// app/composables/seo/useAboutSeo.ts
import type { PageSeoInput } from '~/composables/usePageSeo'

const aboutSeoDefaults: PageSeoInput = {
  title: 'Về chúng tôi - HISOTECH',
  description: 'Tìm hiểu về đội ngũ và sứ mệnh của HISOTECH.',
  type: 'website',
  image: '/images/about-og.jpg',   // optional
}

export const useAboutSeo = () => {
  usePageSeo(aboutSeoDefaults)
}
```

### Bước 2: Gọi trong page component

```vue
<!-- app/pages/about.vue -->
<script setup>
useAboutSeo()
</script>
```

### Bước 3 (nếu cần): SEO động từ API

```vue
<script setup>
const { data: product } = await useFetch('/api/products/1')

usePageSeo(computed(() => ({
  title: `${product.value?.name} - HISOTECH`,
  description: product.value?.description || '',
  image: product.value?.image,
  type: 'article',
})))
</script>
```

---

## 🌐 SEO đa ngôn ngữ (i18n)

Hệ thống **tự động** xử lý:

| Tính năng | Cách hoạt động |
|-----------|---------------|
| `og:locale` | Tự chuyển `vi_VN` ↔ `en_US` theo locale hiện tại |
| URL routing | `/` = VN, `/en/` = EN (strategy: `prefix_except_default`) |
| Canonical | Tự tạo theo URL hiện tại |

Để SEO title/description theo ngôn ngữ:

```ts
// Dùng i18n trong composable
export const useAboutSeo = () => {
  const { t } = useI18n()

  usePageSeo(computed(() => ({
    title: t('seo.aboutTitle'),         // key trong vi.json / en.json
    description: t('seo.aboutDesc'),
  })))
}
```

---

## ⚡ Performance

Các meta tĩnh (OG, Twitter, robots) được wrap trong `if (import.meta.server)`:

```ts
// Chỉ chạy trên server — bot Google đọc HTML server-rendered
if (import.meta.server) {
  useSeoMeta({
    ogTitle: () => ...,
    twitterCard: () => ...,
  })
}

// Title chạy cả client — user thấy trên tab browser
useSeoMeta({
  title: () => seoValue.value.title,
})
```

**Tại sao?** Bot Google chỉ đọc HTML từ server. User trên browser không cần OG tags → bỏ qua trên client = nhanh hơn.

---

## ✅ Checklist SEO cho trang mới

- [ ] Tạo composable SEO (`useXxxSeo.ts`) với title + description
- [ ] Gọi composable trong `<script setup>` của page
- [ ] Title ≤ 60 ký tự, description ≤ 160 ký tự
- [ ] Có ảnh OG (1200×630px lý tưởng) nếu trang quan trọng
- [ ] Thêm translation keys nếu trang cần đa ngôn ngữ
- [ ] Test bằng [Facebook Debugger](https://developers.facebook.com/tools/debug/) hoặc [Google Rich Results](https://search.google.com/test/rich-results)
- [ ] Kiểm tra `robots` đúng (`index,follow` hay `noindex`)

# SEO Sau Refactor

Tài liệu này mô tả kiến trúc SEO hiện tại, thay cho mô tả cũ chỉ nói về `useHomeSeo`.

## 1. Các lớp SEO hiện tại

### Layer 1: helper chung
- [seo.helpers.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/seo/seo.helpers.ts)

Chứa:
- `PageSeoInput`
- `createSeoInput()`
- `resolveSeoImage()`
- `toOgLocale()`
- `useSeoContext()`

### Layer 2: áp meta tags
- [usePageSeo.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/seo/usePageSeo.ts)

Composable gốc để set:
- title
- description
- robots
- Open Graph
- Twitter Card
- canonical
- favicon nếu có

### Layer 3: fetch + build input SEO
- [useEntitySeo.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/seo/useEntitySeo.ts)
- [useHomeSeo.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/seo/useHomeSeo.ts)

### Layer 4: structured data
- [useJsonLd.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/seo/useJsonLd.ts)

Composable này dùng để inject JSON-LD scripts vào `<head>`.

## 2. useSeoContext dùng để làm gì

[useSeoContext()](/d:/Duong/src/laragon/www/hst/client/app/composables/seo/seo.helpers.ts)

Nó trả:
- `localeCode`
- `siteUrl`
- `canonicalUrl`

Đây là chỗ chung để:
- mọi SEO composable dùng cùng một canonical rule
- mọi schema JSON-LD dùng chung `siteUrl`
- không lặp logic đọc locale/route/config ở từng nơi

## 3. Luồng SEO của page thường

Page thường như `/services`, `/about`, `/contact`:

1. page route gọi `useSingleSlugPage()`
2. composable đó gọi `useEntitySeo(pageSlug.value)`
3. `useEntitySeo()` fetch `/api/pages/{slug}/meta`
4. dữ liệu meta được convert qua `createSeoInput()`
5. `usePageSeo()` áp meta tags

## 4. Luồng SEO của detail page

Detail page như `/blog/some-post`:

1. route detail gọi `useDetailRoutePage()`
2. composable đó gọi `useEntitySeo(detail.value)`
3. meta cơ bản tới từ `/api/pages/{detail}/meta`
4. detail component có thể thêm JSON-LD riêng bằng `useJsonLd()`

Tức là:
- meta cơ bản dùng API meta
- structured data dùng data detail thực tế

## 5. Structured data hiện có

Hiện project đang có:

- [Blog.vue](/d:/Duong/src/laragon/www/hst/client/app/components/pages/details/Blog.vue)
  - `BlogPosting`

- [index.vue](/d:/Duong/src/laragon/www/hst/client/app/pages/blog/index.vue)
  - `CollectionPage`
  - `ItemList`

- [Service.vue](/d:/Duong/src/laragon/www/hst/client/app/components/pages/details/Service.vue)
  - `Service`

- [Website-demo.vue](/d:/Duong/src/laragon/www/hst/client/app/components/pages/details/Website-demo.vue)
  - `SoftwareApplication`

- [faqs.vue](/d:/Duong/src/laragon/www/hst/client/app/components/shortcode/faqs.vue)
  - `FAQPage`

## 6. Khi nào dùng useEntitySeo, khi nào dùng useJsonLd

### Dùng `useEntitySeo`
Khi cần meta cơ bản của một entity/page:
- title
- description
- canonical
- og/tw card

### Dùng `useJsonLd`
Khi cần structured data cho search engines:
- `BlogPosting`
- `FAQPage`
- `Service`
- `SoftwareApplication`
- `CollectionPage`

Hai cái này không thay thế nhau. Thường sẽ dùng cả hai.

## 7. Cách thêm SEO cho page mới

### Case 1: page thường có API meta

Chỉ cần:

```ts
useEntitySeo(slug)
```

### Case 2: detail page cần schema riêng

Ví dụ:

```ts
const productSchema = computed(() => ({
  "@context": "https://schema.org",
  "@type": "Product",
  name: product.value.name,
  url: canonicalUrl.value,
}));

useJsonLd(productSchema);
```

## 8. Quy tắc nên giữ

- canonical luôn lấy từ `useSeoContext`
- absolute image luôn đi qua `resolveSeoImage()` hoặc helper tương đương
- meta cơ bản không viết trực tiếp trong component nếu đã có `useEntitySeo`
- JSON-LD chỉ tạo từ dữ liệu thực sự có trong page
- không hardcode URL domain trong component

## 9. Checklist debug SEO

Nếu SEO không đúng, kiểm tra:

1. `/api/pages/{slug}/meta` có dữ liệu chưa
2. `createSeoInput()` có fallback sai không
3. `canonicalUrl` có đúng route hiện tại không
4. image là absolute URL chưa
5. JSON-LD có tạo trên server không
6. key locale có đúng `vi_VN` / `en_US` không

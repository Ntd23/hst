# Luồng Page Detail

Tài liệu này mô tả luồng của các trang detail sau refactor.

Ví dụ:
- `/blog/some-post`
- `/services/some-service`
- `/web-demos/some-demo`

## 1. Route detail đi qua đâu

Route động detail nằm ở:
- [[page]/[detail].vue](/d:/Duong/src/laragon/www/hst/client/app/pages/[page]/[detail].vue)

Page này dùng:
- [useDetailRoutePage.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/pages/useDetailRoutePage.ts)

Composable này làm 3 việc:
1. lấy `page` từ `route.params.page`
2. lấy `detail` từ `route.params.detail`
3. map `page` sang detail component bằng [useMappedDetailPage.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/common/useMappedDetailPage.ts)

Đồng thời nó gọi:
- `useEntitySeo(detail.value)`

Tức là SEO của detail page được áp ngay từ route-level.

## 2. useMappedDetailPage chọn component nào

[useMappedDetailPage.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/common/useMappedDetailPage.ts)

Nó normalize `page`:
- lower-case
- bỏ `s` cuối nếu có

Ví dụ:
- `blog` -> `blog`
- `services` -> `service`

Sau đó trả về component detail tương ứng, ví dụ:
- [Blog.vue](/d:/Duong/src/laragon/www/hst/client/app/components/pages/details/Blog.vue)
- [Service.vue](/d:/Duong/src/laragon/www/hst/client/app/components/pages/details/Service.vue)
- [Website-demo.vue](/d:/Duong/src/laragon/www/hst/client/app/components/pages/details/Website-demo.vue)

## 3. Data detail được fetch thế nào

Detail components hiện không gọi API thẳng. Chúng dùng page-level composables:

- [useBlogDetailPage.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/pages/useBlogDetailPage.ts)
- [useServiceDetailPage.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/pages/useServiceDetailPage.ts)
- [useWebsiteDemoDetailPage.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/pages/useWebsiteDemoDetailPage.ts)

Các composable này thường đều dựa trên:
- [usePageDetail.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/content/usePageDetail.ts)

`usePageDetail.ts` fetch:

```ts
useFetch(`/api/pages/${pageSlug}/details`, {
  key: `details-${pageSlug}-${localeCode.value}`,
  query: { locale: localeCode.value },
  transform: (res) => res?.data ?? res,
})
```

## 4. Vai trò của page-level composable

Mục tiêu của `useXxxDetailPage` là:
- normalize data từ API
- format ngày
- bóc riêng data mà UI cần
- gom logic render-specific ra khỏi component

Ví dụ:

### Blog detail
[useBlogDetailPage.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/pages/useBlogDetailPage.ts)

Trả:
- `post`
- `recentPosts`
- `categories`
- `tags`
- `pending`

### Service detail
[useServiceDetailPage.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/pages/useServiceDetailPage.ts)

Trả:
- `pageData`
- `Shortcodes`
- `sidebarServices`
- `recentPosts`
- `handbookItems`
- `cleanContent`

### Website demo detail
[useWebsiteDemoDetailPage.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/pages/useWebsiteDemoDetailPage.ts)

Trả:
- `pageData`
- `dataWeb`
- `route`

## 5. Detail page có thể render shortcode không

Có.

Riêng [Service.vue](/d:/Duong/src/laragon/www/hst/client/app/components/pages/details/Service.vue) đang render thêm shortcode từ data detail.

Tức là một detail page vẫn có thể chứa shortcode, nếu API trả phần nội dung kiểu section.

## 6. Pattern nên giữ

Đối với detail page:

- component detail chỉ render
- composable page xử lý data shape
- composable nhỏ hơn xử lý concern riêng

Ví dụ mới:
- [useBlogDetailShare.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/pages/useBlogDetailShare.ts)

Composable này tách riêng:
- share labels
- share URLs
- absolute image URL

để [Blog.vue](/d:/Duong/src/laragon/www/hst/client/app/components/pages/details/Blog.vue) không ôm thêm logic business.

## 7. Khi thêm một detail page mới

Ví dụ thêm `/case-studies/[slug]`

Nên làm theo thứ tự:

1. thêm component detail trong `components/pages/details/`
2. thêm page composable `useCaseStudyDetailPage.ts`
3. đăng ký vào `getDetailComponents.ts`
4. nếu có SEO schema riêng, thêm JSON-LD trong detail component hoặc composable liên quan
5. chỉ để component nhận data đã normalize

## 8. Checklist debug

Nếu detail page không lên đúng:

1. `route.params.page` map đúng component chưa
2. `/api/pages/{detail}/details` có dữ liệu chưa
3. `usePageDetail` có transform đúng chưa
4. composable page có normalize đúng shape API chưa
5. SEO có đang gọi nhầm theo `page` thay vì `detail` không

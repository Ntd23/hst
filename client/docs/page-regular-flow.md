# Luồng Page Thường

Tài liệu này mô tả luồng của các trang "thường" sau refactor, tức là các trang render bằng `PageRenderer` và danh sách shortcode từ API.

Ví dụ:
- `/`
- `/services`
- `/lien-he-voi-chung-toi`
- các slug đơn không có `[detail]`

## 1. Route vào page

Page route động nằm ở:
- [index.vue](/d:/Duong/src/laragon/www/hst/client/app/pages/index.vue)
- [[page]/index.vue](/d:/Duong/src/laragon/www/hst/client/app/pages/[page]/index.vue)

Luồng route động:
1. lấy `route.params.page`
2. gọi [useSingleSlugPage.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/pages/useSingleSlugPage.ts)
3. composable này:
   - resolve `pageSlug`
   - gọi `useEntitySeo(pageSlug.value)`
4. page render [PageRenderer.vue](/d:/Duong/src/laragon/www/hst/client/app/components/commons/renderers/PageRenderer.vue)

## 2. PageRenderer làm gì

[PageRenderer.vue](/d:/Duong/src/laragon/www/hst/client/app/components/commons/renderers/PageRenderer.vue) chỉ làm 3 việc:

1. gọi [usePageRenderer.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/common/usePageRenderer.ts)
2. hiển thị skeleton khi `pending`
3. loop `Shortcodes` và render component tương ứng

Nó không tự fetch API hay tự quyết định shortcode nào map vào component nào. Phần đó nằm ở composable.

## 3. usePageRenderer lấy data thế nào

[usePageRenderer.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/common/usePageRenderer.ts)

Luồng bên trong:
1. nhận `slug`
2. gọi [usePageSections.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/content/usePageSections.ts)
3. lấy `pageData.sections`
4. đưa mảng `sections` vào [useMappedShortcodes.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/common/useMappedShortcodes.ts)
5. trả ra:
   - `pageData`
   - `pending`
   - `Shortcodes`
   - `pageTitle`

## 4. usePageSections fetch cái gì

[usePageSections.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/content/usePageSections.ts)

Composable này là data-layer cho page thường:

```ts
useFetch(`/api/pages/${pageSlug}/sections`, {
  key: `sections-${pageSlug}-${localeCode.value}`,
  query: { locale: localeCode.value },
  transform: (res) => res?.data ?? res,
})
```

Ý nghĩa:
- fetch theo `slug`
- cache theo `slug + locale`
- luôn gửi `locale`
- flatten response để component phía trên không phải xử lý `res.data.data`

## 5. useMappedShortcodes map shortcode ra component

[useMappedShortcodes.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/common/useMappedShortcodes.ts)

Ví dụ API trả:

```json
{
  "shortcode": "services",
  "content": { "...": "..." }
}
```

Composable sẽ:
1. lấy `shortcode = "services"`
2. convert thành `Services`
3. tìm key `ShortcodeServices` trong [getShortcodeComponents.ts](/d:/Duong/src/laragon/www/hst/client/app/utils/getShortcodeComponents.ts)
4. trả về:

```ts
{
  component: ShortcodeServices,
  data: section.content
}
```

Sau đó `PageRenderer` chỉ việc:

```vue
<component :is="Shortcode.component" :data="Shortcode.data" />
```

## 6. Kiến trúc đúng sau refactor

Pattern hiện tại:

- `pages/*`: resolve route
- `composables/pages/*`: orchestration theo loại page
- `composables/content/*`: fetch page data dùng chung
- `composables/common/*`: mapping / app-wide helpers
- `components/commons/renderers/*`: render page shell
- `components/shortcode/*`: render từng shortcode
- `composables/shortcodes/*`: logic riêng của shortcode

## 7. Khi thêm một page thường mới

Nếu backend đã trả `sections` đúng shape, thường không cần tạo page component mới.

Chỉ cần:
1. backend trả slug mới qua `/api/pages/{slug}/sections`
2. nếu dùng shortcode mới:
   - thêm component trong `components/shortcode/`
   - thêm composable `useXxxShortcode.ts` nếu cần logic
   - đăng ký trong `getShortcodeComponents.ts`

## 8. Checklist debug

Nếu page thường không render đúng, kiểm tra theo thứ tự:

1. `/api/pages/{slug}/sections` có trả data không
2. `shortcode` trong API có đúng tên không
3. `getShortcodeComponents.ts` có key tương ứng không
4. component shortcode có đang đọc đúng `props.data` không
5. composable shortcode có đang normalize đúng shape `content` không

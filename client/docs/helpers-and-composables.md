# Helper Và Composable: Cách Dùng

Tài liệu này là cheat sheet cho các helper/composable chính sau refactor.

## 1. Chia nhóm composables hiện tại

### `composables/common/`
Dùng cho logic dùng chung toàn app:
- [useI18nText.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/common/useI18nText.ts)
- [useHeader.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/common/useHeader.ts)
- [useAppHeader.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/common/useAppHeader.ts)
- [useAppFooter.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/common/useAppFooter.ts)
- [usePageRenderer.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/common/usePageRenderer.ts)
- [useMappedShortcodes.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/common/useMappedShortcodes.ts)
- [useMappedDetailPage.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/common/useMappedDetailPage.ts)
- [useCommonCardText.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/common/useCommonCardText.ts)

### `composables/content/`
Data fetch dùng chung cho page/entity:
- [usePageSections.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/content/usePageSections.ts)
- [usePageDetail.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/content/usePageDetail.ts)

### `composables/blog/`
Data fetch cho domain blog:
- [useBlogListing.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/blog/useBlogListing.ts)

### `composables/pages/`
Logic orchestration ở mức page:
- [useSingleSlugPage.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/pages/useSingleSlugPage.ts)
- [useDetailRoutePage.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/pages/useDetailRoutePage.ts)
- [useBlogListingPage.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/pages/useBlogListingPage.ts)
- [useBlogDetailPage.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/pages/useBlogDetailPage.ts)
- [useBlogDetailShare.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/pages/useBlogDetailShare.ts)
- [useServiceDetailPage.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/pages/useServiceDetailPage.ts)
- [useWebsiteDemoDetailPage.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/pages/useWebsiteDemoDetailPage.ts)

### `composables/shortcodes/`
Mỗi shortcode có một composable riêng để component chỉ render.

Ví dụ:
- [useServicesShortcode.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/shortcodes/useServicesShortcode.ts)
- [useFaqsShortcode.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/shortcodes/useFaqsShortcode.ts)
- [useContactSectionForm.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/shortcodes/useContactSectionForm.ts)

## 2. useI18nText dùng khi nào

[useI18nText.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/common/useI18nText.ts)

Dùng khi cần:
- `localeCode`
- `translate(key, fallback)`

Ví dụ:

```ts
const { translate, localeCode } = useI18nText();

const title = computed(() =>
  translate("blogDetail.tags", localeCode.value === "en" ? "Tags" : "Thẻ")
);
```

Quy tắc:
- component/composable client-side nên dùng `translate()` thay vì tự gọi `useI18n()` lặp lại ở nhiều chỗ

## 3. useCommonCardText dùng khi nào

[useCommonCardText.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/common/useCommonCardText.ts)

Dùng cho:
- format ngày
- text lặp như `read more`, `detail`, `by`

Ví dụ:

```ts
const { formatDate, detailLabel } = useCommonCardText();
```

Phù hợp với:
- blog cards
- product cards
- listing cards

## 4. useHeader / useAppHeader khác nhau thế nào

### `useHeader`
- fetch data header từ API
- là data-layer

### `useAppHeader`
- dùng `useHeader`
- xử lý logic UI của header:
  - locale
  - sticky / scroll
  - menu state
  - contact button link

Quy tắc:
- component layout nên dùng `useAppHeader`
- không gọi `useHeader` trực tiếp trong `AppHeader.vue`

## 5. usePageSections / usePageDetail / useBlogListing

Đây là nhóm fetch chính:

### `usePageSections(slug)`
- dùng cho page render theo shortcode
- endpoint: `/api/pages/{slug}/sections`

### `usePageDetail(slug)`
- dùng cho detail entity/page
- endpoint: `/api/pages/{slug}/details`

### `useBlogListing(params)`
- dùng cho listing blog
- endpoint: `/api/blog/listing`

Quy tắc:
- composable fetch ở nhóm này không nên ôm logic UI
- chỉ lo `useFetch`, `key`, `locale`, `transform`

## 6. useMappedShortcodes / useMappedDetailPage

### `useMappedShortcodes`
Map:
- `section.shortcode`
- sang component shortcode tương ứng

### `useMappedDetailPage`
Map:
- `route.params.page`
- sang detail component tương ứng

Hai composable này là lớp registry/mapping, không fetch data.

## 7. useXxxShortcode pattern

Pattern chuẩn hiện tại:

```ts
export const useServicesShortcode = (data: MaybeRefOrGetter<any>) => {
  const sectionData = computed(() => {
    return {};
  });

  return {
    sectionData,
  };
};
```

Component shortcode:

```vue
<script setup lang="ts">
const props = defineProps<{ data?: any }>();
const { sectionData } = useServicesShortcode(toRef(props, "data"));
</script>
```

Quy tắc:
- component chỉ render
- composable normalize data/API shape
- animation/timer/observer cũng ưu tiên nằm ở composable

## 8. useContactSectionForm là pattern tốt cho form

[useContactSectionForm.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/shortcodes/useContactSectionForm.ts)

Nó đang gom:
- normalize section config
- form state
- labels
- errors
- submit
- focus field lỗi đầu tiên
- success state

Đây là pattern nên copy khi có form khác.

## 9. Khi nào cần tạo composable mới

Nên tạo composable mới khi:
- component có nhiều `computed`, `watch`, `onMounted`
- component phải normalize API shape
- logic đó có thể test/đọc riêng tốt hơn nếu tách ra

Không cần tách nếu:
- component chỉ có 1-2 computed đơn giản
- chỉ render props thẳng

## 10. Quy tắc đặt tên

- shortcode: `useXxxShortcode`
- page-level: `useXxxPage` hoặc `useXxxDetailPage`
- common app logic: `useXxx`
- SEO: `useXxxSeo`

Ví dụ tốt:
- `useServicesShortcode`
- `useBlogDetailPage`
- `useBlogDetailShare`
- `usePageRenderer`

## 11. Checklist trước khi thêm helper/composable

Trước khi tạo mới, hỏi:

1. logic này có lặp ở file khác không
2. đây là data-layer, page-layer, hay render-layer
3. tên file có nói rõ responsibility không
4. component sau khi tách có gọn và dễ đọc hơn không

# Widget Flow

## Mục tiêu

Widget hiện được chia thành 2 nhánh rõ ràng:

- `layout widgets`: widget dùng cho shell chung của site
- `sidebar widgets`: widget theo ngữ cảnh trang

Mục tiêu của flow này là:

- backend trả dữ liệu theo đúng `sidebar`
- frontend chỉ gọi đúng endpoint theo đúng ngữ cảnh
- mỗi widget có handler backend và component frontend riêng

## 1. Phân loại widget

### Layout widgets

Dùng cho các vùng global:

- `menu_sidebar`
- `header_top_start_sidebar`
- `header_top_end_sidebar`
- `top_footer_sidebar`
- `footer_sidebar`
- `bottom_footer_sidebar`

Các vùng này được trả từ:

- `GET /api/widgets/layout`

### Sidebar widgets

Dùng theo loại trang:

- `primary_sidebar`
- `blog_sidebar`
- `service_sidebar`
- `product_sidebar`

Các vùng này được trả từ:

- `GET /api/widgets/sidebar?type=primary`
- `GET /api/widgets/sidebar?type=blog`
- `GET /api/widgets/sidebar?type=service`
- `GET /api/widgets/sidebar?type=product`

## 2. Backend flow

### Route

File:

- [api.php](/d:/Duong/src/laragon/www/hst/admin/routes/api.php)

Route hiện có:

```php
Route::prefix('widgets')->group(function () {
    Route::get('layout', [WidgetController::class, 'getLayoutWidgets']);
    Route::get('sidebar', [WidgetController::class, 'getSidebarWidgets']);
});
```

### Controller

File:

- [WidgetController.php](/d:/Duong/src/laragon/www/hst/admin/app/Http/Controllers/Api/WidgetController.php)

Controller chia ra:

- `getLayoutWidgets()`
- `getSidebarWidgets()`

#### Layout payload

`getLayoutWidgets()` trả:

- `locale`
- `theme`
- `settings`
- `menu_sidebar`
- `header_top_start`
- `header_top_end`
- `top_footer`
- `footer`
- `bottom_footer`

Mỗi vùng layout có shape:

```json
{
  "type": "footer",
  "sidebar": "footer_sidebar",
  "items": []
}
```

#### Sidebar payload

`getSidebarWidgets()` trả:

```json
{
  "locale": "vi",
  "type": "blog",
  "sidebar": "blog_sidebar",
  "items": []
}
```

### Sidebar type mapping

Trong `WidgetController`:

- `blog` -> `blog_sidebar`
- `service` -> `service_sidebar`
- `product` -> `product_sidebar`
- `menu` -> `menu_sidebar`
- `header-top-start` -> `header_top_start_sidebar`
- `header-top-end` -> `header_top_end_sidebar`
- `primary` / `page` -> `primary_sidebar`

Lưu ý:

- `menu`, `header-top-start`, `header-top-end` hiện vẫn có thể gọi qua `/widgets/sidebar` để debug
- nhưng frontend chính thức nên lấy chúng qua `/widgets/layout`

### Widget normalization

File:

- [WidgetApiTrait.php](/d:/Duong/src/laragon/www/hst/admin/app/Http/Controllers/Api/Traits/WidgetApiTrait.php)

Vai trò:

- resolve media url
- parse social links
- parse contact info items
- normalize repeater data
- query widget theo locale/theme

Locale/theme hiện hỗ trợ cả trường hợp:

- `locale=vi` -> `apexa`
- `locale=en` -> tự tìm `apexa-en_US` nếu tồn tại

### Widget service + manager

Files:

- [WidgetService.php](/d:/Duong/src/laragon/www/hst/admin/app/Services/WidgetService.php)
- [WidgetManager.php](/d:/Duong/src/laragon/www/hst/admin/app/Widget/Core/WidgetManager.php)

Flow:

1. controller lấy danh sách widget raw từ DB
2. `WidgetService::allWidgets(...)`
3. `WidgetManager::getWidgets(...)`
4. manager resolve handler class theo tên widget
5. handler trả `content` đã chuẩn hóa

Ví dụ:

- `ContactInformationWidget` -> [ContactInformationWidget.php](/d:/Duong/src/laragon/www/hst/admin/app/Widget/Handlers/ContactInformationWidget.php)
- `SiteInformationWidget` -> [SiteInformationWidget.php](/d:/Duong/src/laragon/www/hst/admin/app/Widget/Handlers/SiteInformationWidget.php)
- `SocialLinksWidget` -> [SocialLinksWidget.php](/d:/Duong/src/laragon/www/hst/admin/app/Widget/Handlers/SocialLinksWidget.php)

## 3. Nuxt server flow

### Layout proxy

File:

- [layout.get.ts](/d:/Duong/src/laragon/www/hst/client/server/api/widgets/layout.get.ts)

Vai trò:

- proxy request từ Nuxt sang Laravel API `/widgets/layout`

### Sidebar proxy

File:

- [sidebar.get.ts](/d:/Duong/src/laragon/www/hst/client/server/api/widgets/sidebar.get.ts)

Vai trò:

- proxy request từ Nuxt sang Laravel API `/widgets/sidebar`
- truyền `type`

## 4. Frontend store/composable flow

### Layout widgets

Files:

- [layout-widget.ts](/d:/Duong/src/laragon/www/hst/client/app/stores/layout-widget.ts)
- [useLayoutWidgets.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/layout/useLayoutWidgets.ts)
- [useAppWidget.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/layout/useAppWidget.ts)
- [AppWidget.vue](/d:/Duong/src/laragon/www/hst/client/app/components/layout/AppWidget.vue)

Flow:

1. `useLayoutWidgets()` gọi store `layout-widget`
2. store fetch `/api/widgets/layout`
3. `useAppWidget()` lấy các vùng:
   - `top_footer`
   - `footer`
   - `bottom_footer`
4. `useMappedWidgets()` map từng item sang Vue component
5. `AppWidget.vue` render

### Sidebar widgets

Files:

- [sidebar-widget.ts](/d:/Duong/src/laragon/www/hst/client/app/stores/sidebar-widget.ts)
- [useSidebarWidgets.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/content/useSidebarWidgets.ts)
- [SidebarWidgets.vue](/d:/Duong/src/laragon/www/hst/client/app/components/commons/renderers/SidebarWidgets.vue)

Flow:

1. page composable gọi `useSidebarWidgets(type)`
2. store fetch `/api/widgets/sidebar?type=...`
3. page detail/listing nhận `sidebarWidgetData`
4. `SidebarWidgets.vue` render danh sách widget

Hiện đang dùng ở:

- [useBlogDetailPage.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/pages/useBlogDetailPage.ts)
- [useBlogListingPage.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/pages/useBlogListingPage.ts)
- [useServiceDetailPage.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/pages/useServiceDetailPage.ts)

Để tránh hardcode lặp `blog`, `service`, `product`, frontend dùng thêm helper:

- [useSidebarType.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/shared/useSidebarType.ts)

Ví dụ:

```ts
const { resolveSidebarTypeFromPage } = useSidebarType();
const { sidebarWidgetData } = useSidebarWidgets(
  resolveSidebarTypeFromPage("blog")
);
```

## 5. Map widget name -> component

Files:

- [useMappedWidgets.ts](/d:/Duong/src/laragon/www/hst/client/app/composables/layout/useMappedWidgets.ts)
- [getWidgetComponents.ts](/d:/Duong/src/laragon/www/hst/client/app/utils/getWidgetComponents.ts)

Flow:

1. backend trả `widget`, ví dụ:
   - `newsletter`
   - `site-information`
   - `blog-search`
2. frontend dùng `import.meta.glob()` load `client/app/components/widget/*.vue`
3. map widget key sang component tương ứng

Ví dụ:

- `newsletter` -> [newsletter.vue](/d:/Duong/src/laragon/www/hst/client/app/components/widget/newsletter.vue)
- `site-information` -> [site-information.vue](/d:/Duong/src/laragon/www/hst/client/app/components/widget/site-information.vue)
- `contact-information` -> [contact-information.vue](/d:/Duong/src/laragon/www/hst/client/app/components/widget/contact-information.vue) nếu có

## 6. Cách thêm widget mới

### Bước 1: tạo backend handler

Tạo file trong:

- `admin/app/Widget/Handlers`

Ví dụ:

- `MyCustomWidget.php`

Handler cần:

- implement `WidgetInterface`
- có `public static function widget(): string`
- có `handle(array $widget, string $locale): array|string|null`

### Bước 2: đảm bảo widget key đúng

`WidgetManager` map handler từ tên widget theo quy tắc:

- `my-custom` -> `App\Widget\Handlers\MyCustomWidget`

Nên tên class và `widget()` phải khớp logic này.

### Bước 3: tạo component frontend

Tạo file:

- `client/app/components/widget/my-custom.vue`

Component chỉ nên:

- nhận `data`
- render UI

Không nên tự fetch lại API nếu không cần.

### Bước 4: test endpoint

Nếu là widget global:

```http
GET /api/widgets/layout?locale=vi
```

Nếu là widget page-specific:

```http
GET /api/widgets/sidebar?locale=vi&type=blog
```

### Bước 5: kiểm tra frontend render

Nếu backend trả đúng:

- `widget`
- `content`

thì `useMappedWidgets()` sẽ tự map nếu file Vue tồn tại đúng tên.

## 7. Quy ước nên giữ

- `layout` chỉ dùng cho global widgets
- `sidebar` chỉ dùng cho page/context widgets
- không nhét widget layout vào page composable
- không để widget component tự xử lý fetch nếu backend đã chuẩn hóa đủ data
- nếu có locale ngắn như `en`, backend phải tự resolve sang theme localized thật như `apexa-en_US`

## 8. Endpoint test nhanh

### Layout

```http
GET /api/widgets/layout?locale=vi
GET /api/widgets/layout?locale=en
```

### Sidebar

```http
GET /api/widgets/sidebar?locale=vi&type=primary
GET /api/widgets/sidebar?locale=vi&type=blog
GET /api/widgets/sidebar?locale=vi&type=service
GET /api/widgets/sidebar?locale=vi&type=product
```

### Debug thêm

```http
GET /api/widgets/sidebar?locale=vi&type=menu
GET /api/widgets/sidebar?locale=vi&type=header-top-start
GET /api/widgets/sidebar?locale=vi&type=header-top-end
```

Các type debug này vẫn hữu ích để kiểm tra từng vùng riêng, nhưng frontend chính thức nên dùng `widgets/layout` cho chúng.

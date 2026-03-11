# API Pages: Home + Contact (architecture + helper selection)

Tài liệu này mô tả kiến trúc chung của Page API (Home/Contact/…),
và **cách chọn helper một cách khách quan** dựa trên nguồn dữ liệu.

---

## 1. Tổng quan endpoint (ví dụ)

**Home**
- `GET /api/pages/home/meta?locale=vi`
- `GET /api/pages/home/section/{section}?locale=vi`

**Contact**
- `GET /api/pages/contact/meta?locale=vi`
- `GET /api/pages/contact/section/form?locale=vi`
- `GET /api/pages/contact/section/google-map?locale=vi`
- `POST /api/pages/contact/section/form`

Tất cả endpoint đều xử lý `locale` + cache bằng `ShortcodeApiTrait`.

---

## 2. Quy tắc chọn helper (khách quan)

### 2.0. Sơ đồ quyết định (nhìn nhanh)

```
Có page_id trong theme_option?
├─ Có → dùng getPageShortcode(...) / metaResponse(...)
└─ Không
   ├─ Có shortcode đặc trưng để tìm page?
   │  ├─ Có → getShortcodeFromAnyPage(...) / metaResponseFromShortcode(...)
   │  └─ Không → phải định nghĩa rule tìm page khác (custom)
   └─ Shortcode có body/content?
      ├─ Có → getShortcodeDataFromAnyPage(...)
      └─ Không → getShortcodeFromAnyPage(...)
```

### 2.1. Chọn theo **nguồn Page**

**A. Page cố định qua `theme_option`**  
Dùng khi có key như `homepage_id`, `about_page_id`, …

- Meta: `metaResponse($request, $pageOptionKey, $cacheKeyPrefix)`
- Section: `getPageShortcode($pageOptionKey, $shortcodeName, $locale)`

**B. Page không cố định, xác định bằng shortcode**  
Dùng khi **không có** page_id trong theme_option, nhưng page có shortcode đặc trưng.

- Meta: `metaResponseFromShortcode($request, $shortcodeName, $cacheKeyPrefix)`
- Section: `getShortcodeFromAnyPage($shortcodeName, $locale)`

### 2.2. Chọn theo **kiểu dữ liệu shortcode**

- **Chỉ cần attributes** → `getPageShortcode(...)` hoặc `getShortcodeFromAnyPage(...)`
- **Cần cả content** (shortcode có body) → `getShortcodeDataFromAnyPage(...)`

### 2.3. Khi nào dùng `getPageByShortcode()`

Dùng khi cần **Page model** để lấy meta/seo của chính trang đó
trong trường hợp không có theme_option.

---

## 3. Kiến trúc mẫu theo controller

### 3.1. HomeController (page cố định)

**Tính chất:** page lấy bằng `homepage_id` (theme_option).

**Pattern chung:**
1. `metaResponse($request, 'homepage_id', 'home')`
2. Section:
   - `sectionResponse(...)`
   - `getPageShortcode('homepage_id', '{shortcode}', $locale)`
   - parse attrs → query model nếu cần (slider, services, team, …)

### 3.2. ContactController (page theo shortcode)

**Tính chất:** không cần `contact_page_id`, page xác định bởi shortcode.

**Pattern chung:**
1. `metaResponseFromShortcode($request, 'contact-form', 'contact')`
2. Section form:
   - `getShortcodeFromAnyPage('contact-form', $locale)` → attrs
3. Section google-map:
   - `getShortcodeDataFromAnyPage('google-map', $locale)` → attrs + content
4. Submit form:
   - delegate `PublicController::postSendContact`

### 3.3. ServiceController (page theo shortcode + ưu tiên page “đúng”)

**Tính chất:** nhiều page có thể chứa `[services ...]` (homepage + service page).

**Pattern chung:**
1. `GET /api/pages/service/section/list`
2. Nếu có `?page_id=...` → lấy shortcode từ đúng page đó.
3. Nếu **không có `page_id`** → tự chọn **page có `[services]` với số `service_ids` lớn nhất** (giống cách web ưu tiên trang dịch vụ).
4. Response trả kèm `page_id` để client có thể cố định lần sau.

---

## 4. Flow chuẩn cho mọi section

1. `sectionResponse($request, '{page}:{section}', callback)`
2. Trong callback:
   - lấy shortcode theo đúng rule ở mục 2
   - parse attrs / tabs / media
   - query model nếu có ID list
3. Trả payload chuẩn (null → 404)u

**Lưu ý khi một shortcode xuất hiện ở nhiều page:**
- Endpoint có thể trả kèm `page_id` để client biết page đang được dùng.
- Client có thể truyền `?page_id=...` để cố định nguồn dữ liệu khi cần.
- **(auto chọn page “đúng nhất”)**: backend tự chọn page theo rule, ví dụ:
  - Shortcode dạng list (`service_ids`, `team_ids`, `testimonial_ids`, …) → chọn page có **nhiều ID nhất**.
  - Shortcode có content → ưu tiên page có **content không rỗng**.

---

## 5. Helper nhanh theo use-case

- **Meta (page fixed)** → `metaResponse`
- **Meta (page by shortcode)** → `metaResponseFromShortcode`
- **Attrs only (page fixed)** → `getPageShortcode`
- **Attrs only (any page)** → `getShortcodeFromAnyPage`
- **Attrs + content (any page)** → `getShortcodeDataFromAnyPage`
- **Parse tabs (quantity + field_N)** → `parseShortcodeTabs`
- **Image path → URL** → `imageUrl`

---

## 6. Quy ước đặt `sectionName`

Nên theo dạng `"{page}:{section}"`, ví dụ:
- `home:simple-slider`
- `contact:contact-form`
- `contact:google-map`

`sectionName` ảnh hưởng tới **cache key** và message 404.

---

## 7. Checklist thêm section mới

1. Tạo method trong controller, dùng `sectionResponse(...)`
2. Chọn helper theo rule mục 2
3. Định nghĩa route trong `admin/routes/api.php`
4. (Optional) viết doc + test curl

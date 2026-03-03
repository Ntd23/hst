# Luồng hoạt động: Browser → Nuxt → Backend

> Dành cho người mới học Nuxt. Tài liệu này giải thích cách request di chuyển từ trình duyệt đến server Laravel thông qua Nuxt.

---

## 1. Tổng quan kiến trúc

```
┌─────────────┐     HTTP      ┌──────────────────┐     HTTP      ┌─────────────┐
│   Browser   │ ────────────► │   Nuxt (Node.js) │ ────────────► │   Laravel   │
│             │ ◄──────────── │   port :3000     │ ◄──────────── │  port :8000 │
└─────────────┘               └──────────────────┘               └─────────────┘
                                       ▲
                                       │ Apache proxy (hst.test)
                                       │ /api/** → Laravel
                                       │ /**    → Nuxt
```

Project này gồm **2 server chạy song song**:
- **Nuxt** (Node.js `:3000`): render giao diện, xử lý i18n, gọi API
- **Laravel** (PHP `:8000`): cung cấp dữ liệu qua REST API

Apache đóng vai trò **reverse proxy** — nhận request từ `hst.test` và forward đến đúng server.

---

## 2. SSR vs CSR — Nuxt render như thế nào?

Nuxt hỗ trợ **Server-Side Rendering (SSR)**. Mỗi lần người dùng truy cập một URL, có 2 giai đoạn:

### Giai đoạn 1 — Server Render (SSR)

```
Browser gõ: hst.test/
     │
     ▼
Apache → Nuxt Node server
     │
     ▼ Nuxt thực thi Vue component trên server
     │   - Fetch API data (useFetch)
     │   - Render HTML hoàn chỉnh
     │
     ▼
Trả về HTML + JSON payload cho browser
```

✅ Browser nhận HTML đã có nội dung → trang hiển thị ngay, SEO tốt.

### Giai đoạn 2 — Hydration (Client-side)

```
Browser nhận HTML
     │
     ▼ Vue.js "hydrate" — gắn event listeners vào HTML đã có
     │   - KHÔNG fetch lại data (đã có trong JSON payload)
     │   - Trang trở nên interactive
     │
     ▼
Trang hoạt động như SPA (Single Page App)
```

> **Quan trọng:** `useFetch` với cùng `key` sẽ **không gọi lại API** ở client nếu data đã có trong SSR payload. Đây là lý do tại sao `key` phải unique và nhất quán.

---

## 3. Luồng API: useFetch → Nuxt Server Route → Laravel

### Ví dụ: Header data

#### Bước 1 — Component gọi composable

```ts
// app/components/layout/AppHeader.vue
const { data: headerResponse } = await useHeader()
```

#### Bước 2 — Composable dùng `useFetch`

```ts
// app/composables/common/useHeader.ts
export const useHeader = () => {
  const { locale } = useI18n()

  return useFetch<any>('/api/common/header', {
    key: `common-header-data-${locale.value}`,  // Cache key theo locale
    query: computed(() => ({ locale: locale.value })),
  })
}
```

`useFetch('/api/common/header')` gọi đến **Nuxt Server Route** (không phải Laravel trực tiếp).

#### Bước 3 — Nuxt Server Route làm proxy

```ts
// server/api/common/header.get.ts
// File này map với URL: /api/common/header
export default defineEventHandler(async (event) => {
  const query = getQuery(event)
  const locale = query.locale || getCookie(event, 'i18n_redirected') || 'vi'

  return apiFetch('/common/header', {   // Gọi Laravel
    query: { locale },
  })
})
```

Server Route nhận vai trò **middleware/proxy**:
- Đọc locale từ query param
- Forward request sang Laravel
- Che giấu URL Laravel khỏi browser (bảo mật)

#### Bước 4 — `apiFetch` gọi Laravel

```ts
// server/utils/apiFetch.ts
export function apiFetch<T>(path: string, opts = {}) {
  const config = useRuntimeConfig()
  return $fetch<T>(path, {
    baseURL: config.apiBaseUrl,  // http://127.0.0.1:8000/api
    ...opts,
  })
}
```

#### Bước 5 — Laravel xử lý và trả về JSON

```php
// admin/routes/api.php
Route::prefix('common')->group(function () {
    Route::get('header', [CommonController::class, 'getHeader']);
});

// CommonController.php
public function getHeader(Request $request) {
    $locale = $request->input('locale', 'vi');
    return Cache::remember("api:header:{$locale}", 300, function () use ($locale) {
        return [
            'logo'      => $this->getLogoData(),
            'main_menu' => $this->getMenuByLocale($locale, false),
            // ...
        ];
    });
}
```

### Sơ đồ đầy đủ

```
AppHeader.vue
  └─ useHeader()
       └─ useFetch('/api/common/header?locale=vi')
            │
            │ [SSR: Nuxt server tự gọi nội bộ]
            │ [CSR: Browser → Apache → Nuxt hoặc Laravel trực tiếp]
            │
            ▼
       server/api/common/header.get.ts
            └─ apiFetch('/common/header?locale=vi')
                 └─ $fetch('http://127.0.0.1:8000/api/common/header?locale=vi')
                      └─ Laravel: CommonController@getHeader
                           └─ JSON { logo, main_menu, ... }
```

---

## 4. Caching — Tại sao không bị fetch nhiều lần?

### Nuxt cache (client)
`useFetch` với `key` sẽ lưu response vào **Nuxt payload**. Khi navigate sang trang khác và quay lại, data không bị fetch lại.

```ts
useFetch('/api/common/header', {
  key: 'common-header-data-vi',  // ← Cùng key = cùng cache
})
```

### Laravel cache (server)
Laravel dùng `Cache::remember()` lưu kết quả **300 giây** (5 phút). Tránh query DB quá nhiều.

```php
Cache::remember("api:header:vi", 300, function () {
    // Query DB và build response — chỉ chạy 1 lần mỗi 5 phút
});
```

---

## 5. Locale — Đa ngôn ngữ hoạt động như thế nào?

### URL-based locale (strategy: prefix)
```
hst.test/        → locale = vi (default)
hst.test/en/     → locale = en
```

### Khi user đổi ngôn ngữ

```
User click [EN]
     │
     ▼
window.location.href = '/en/'   ← Hard reload
     │
     ▼
Browser → Apache → Nuxt SSR với URL /en/
     │
     ▼ Nuxt i18n middleware detect 'en' từ URL
     │
     ▼ useHeader() gọi với locale='en', key='common-header-data-en'
     │
     ▼ server route forward ?locale=en → Laravel
     │
     ▼ Laravel trả menu EN
```

### Tại sao cần locale trong `key`?

```ts
// ❌ Sai — cả vi và en dùng chung cache
key: 'common-header-data'

// ✅ Đúng — mỗi locale có cache riêng
key: `common-header-data-${locale.value}`
```

---

## 6. Thư mục project và vai trò

```
client/
├── app/
│   ├── components/          # Vue components
│   │   └── layout/
│   │       ├── AppHeader.vue   # Header dùng chung toàn site
│   │       └── AppFooter.vue   # Footer dùng chung toàn site
│   ├── composables/         # Logic tái sử dụng
│   │   ├── common/
│   │   │   └── useHeader.ts    # Fetch header data từ API
│   │   └── seo/
│   │       └── useHomeSeo.ts   # SEO cho trang home
│   ├── layouts/
│   │   └── default.vue         # Layout chứa Header + slot + Footer
│   └── pages/
│       └── index.vue           # Trang home (chỉ chứa sections)
│
└── server/
    ├── api/                 # Nuxt Server Routes (proxy)
    │   ├── common/
    │   │   └── header.get.ts   # → Laravel /api/common/header
    │   └── pages/
    │       └── home.get.ts     # → Laravel /api/pages/home
    └── utils/
        └── apiFetch.ts         # Helper gọi Laravel với baseURL
```

---

## 7. Quy tắc đặt tên Server Route

Nuxt tự động map file → URL:

| File | URL |
|---|---|
| `server/api/common/header.get.ts` | `GET /api/common/header` |
| `server/api/pages/home.get.ts` | `GET /api/pages/home` |
| `server/api/user.post.ts` | `POST /api/user` |

> Prefix `/api` là bắt buộc của Nuxt khi file nằm trong `server/api/`. Không được đổi thành `server/routes/` trừ khi muốn URL không có prefix `/api`.

---

## 8. Tóm tắt nhanh

| Khái niệm | Vai trò |
|---|---|
| `useFetch` | Fetch data, tự xử lý SSR + cache theo `key` |
| `Server Route` (`server/api/`) | Proxy trung gian, inject auth/locale, che URL backend |
| `apiFetch` | Helper gọi Laravel với baseURL từ config |
| `useI18n().locale` | Reactive locale value từ URL hoặc cookie |
| `Cache::remember` | Laravel cache kết quả DB query |
| `layouts/default.vue` | Wrap mọi trang với Header và Footer |

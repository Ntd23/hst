## 0) Nguyên tắc cốt lõi (Bắt buộc)

1) **UI Components KHÔNG gọi thẳng API Botble/Laravel.**  
   Component chỉ render props + emit events.

2) **Mọi call backend phải đi qua BFF của Nuxt dưới `/__bff/**`.**  
   Không dùng `/api/_nuxt/**` vì `/api/**` thường bị Apache proxy sang Laravel → client sẽ 404.

3) **Mọi response phải có Type ở `shared/` trước.**  
   Type phải khớp 100% JSON thực tế.

4) **Đa ngôn ngữ: luôn truyền locale.**  
   Dùng query `lang=vi|en` và BFF forward sang Laravel (query + header).

5) **Logic dùng chung phải gom đúng chỗ:**
   - Normalize URL/menu: `server/utils/navigation.ts`
   - Call Botble API: `server/utils/botbleFetch.ts`
   - Types/DTO: `shared/types/**`
   - Composables gọi BFF: `app/composables/**`

---

## 1) Trách nhiệm thư mục

### `app/` (srcDir)
Frontend Nuxt/Vue:
- `app/pages/` — routes
- `app/layouts/` — layout chung
- `app/components/` — UI components (auto import)
- `app/composables/` — composables (auto import)
- `app/utils/` — helper thuần FE (auto import)
- `app/assets/` — CSS, fonts, images bundler xử lý

### `server/` (Nitro / BFF)
Code chạy server-side trong Nuxt:
- `server/routes/__bff/**` — **BFF endpoints** (đường dẫn thực, không prefix `/api`)
- `server/utils/**` — helper server (fetch Botble, normalize data…)
- `server/middleware/**` — middleware server
- `server/plugins/**` — Nitro plugins (**BẮT BUỘC** `export default defineNitroPlugin`)

### `shared/`
Chứa types/DTO dùng chung:
- Chỉ TypeScript types/constants/pure utils
- **Không** được dùng `useFetch`, `ref`, `defineEventHandler`, v.v.

### `modules/`
Nuxt modules nội bộ để “đóng gói chuẩn dự án”:
- runtimeConfig defaults
- routeRules cache
- convention chung cho team

### `public/`
Static assets serve thẳng: logo, robots.txt…

### `content/` (nếu dùng @nuxt/content)
Trang tĩnh bằng markdown (optional).

---

## 2) Quy tắc alias (Rất quan trọng vì `srcDir: 'app/'`)

- `~/...` trỏ vào `app/`
- `~~/...` trỏ vào root project (cùng cấp `nuxt.config.ts`)

Ví dụ đúng:
- Trong `app/*` import shared: `import type {...} from '~~/shared/types/...'`
- Trong `server/*` import server utils: `import { botbleFetch } from '~~/server/utils/botbleFetch'`
- Import file trong app: `import X from '~/components/...'`

Sai thường gặp:
- Dùng `~/server/...` (sai vì `~/` = `app/`)

---

## 3) FORM CODE chuẩn khi thêm 1 feature mới (Bắt buộc)

Mỗi feature backend => follow pipeline:

**(1) Shared Types → (2) Server BFF Route → (3) App Composable → (4) Layout/Page fetch → (5) UI Component render**

### 3.1. (1) Shared Types
- Tạo type theo đúng JSON thực tế backend trả về
- Đặt đúng domain:
  - `shared/types/site/*`
  - `shared/types/navigation/*`
  - `shared/types/blog/*` …

### 3.2. (2) Server BFF Route
- Tạo endpoint trong: `server/routes/__bff/<domain>/...`
- BFF có quyền:
  - gọi Botble nội bộ
  - normalize dữ liệu (nếu cần)
  - forward locale
  - cache bằng routeRules

### 3.3. (3) App Composable
- Nằm trong `app/composables/*`
- Chỉ gọi **BFF** (không gọi Botble trực tiếp)
- `key` phải ổn định và tách theo locale nếu có

### 3.4. (4) Layout/Page fetch
- Fetch ở layout/page (SSR) và truyền data xuống components
- Không fetch trong component UI (trừ component rất đặc biệt có lý do rõ ràng)

### 3.5. (5) UI Component
- Component chỉ nhận `props`
- Không chứa baseURL, không parse absolute URL, không chứa logic fetch

---

## 4) FORM CODE cho đa ngôn ngữ (Bắt buộc)

### 4.1. Nguồn locale
BFF ưu tiên:
1) query `lang`
2) cookie `i18n_redirected` hoặc `locale`
3) default `vi`

### 4.2. BFF forward locale sang Botble
- query: `lang=vi|en`
- header: `X-Locale: vi|en`

### 4.3. Cache phải tách theo locale
- Vì locale nằm trong query (`?lang=en`) nên URL khác nhau → cache tách tự nhiên

---

## 5) Ví dụ chuẩn: Header Topbar

### (1) Shared types
`shared/types/site/header-topbar.ts`
```ts
export type HeaderTopBarData = {
  phone: string | null
  email: string | null
  address: string | null
}

export type HeaderTopBarResponse = {
  data: HeaderTopBarData
}

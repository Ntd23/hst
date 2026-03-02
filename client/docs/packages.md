
> Tài liệu giải thích chức năng từng gói trong `package.json`.

---

## 🔧 Scripts

| Lệnh | Mô tả |
|-------|-------|
| `npm run dev` | Chạy dev server tại `http://localhost:3000` — có Hot Module Reload (HMR), sửa code tự cập nhật trình duyệt |
| `npm run build` | Build production — tạo thư mục `.output/` chứa file tối ưu để deploy lên server |
| `npm run generate` | Build tĩnh (SSG) — tạo HTML tĩnh cho từng trang, phù hợp hosting tĩnh |
| `npm run preview` | Xem trước bản build production trên local |
| `npm run postinstall` | Tự chạy sau `npm install` — tạo types và cấu hình cho Nuxt |

---

## 📁 Dependencies (Gói chạy chính)

### `nuxt` — ^4.2.2
**Framework chính của dự án.** Nuxt là meta-framework xây dựng trên Vue.js, cung cấp:
- **SSR** (Server-Side Rendering): trang được render trên server → SEO tốt, load nhanh
- **Auto-imports**: không cần `import ref, computed...` thủ công
- **File-based routing**: tạo file trong `pages/` = tự có route
- **API routes**: viết backend đơn giản trong `server/`

```bash
# Tài liệu chính
https://nuxt.com/docs
```

---

### `vue` — ^3.5.26
**UI framework cốt lõi.** Nuxt chạy trên Vue 3 (Composition API).
- Dùng `<script setup>` trong mọi component
- Reactivity: `ref()`, `computed()`, `watch()`
- Template syntax: `v-if`, `v-for`, `{{ }}`, `@click`

```vue
<script setup>
const count = ref(0)
</script>
<template>
  <button @click="count++">{{ count }}</button>
</template>
```

---

### `vue-router` — ^4.6.4
**Định tuyến (routing).** Nuxt tự quản lý, dev ít khi cần dùng trực tiếp.
- `pages/index.vue` → route `/`
- `pages/about.vue` → route `/about`
- Dùng `<NuxtLink to="/about">` thay `<a href>`

---

### `@nuxt/ui` — ^4.3.0
**Thư viện UI component chính thức của Nuxt.** Cung cấp 50+ component sẵn:

| Component | Dùng để |
|-----------|---------|
| `<UButton>` | Nút bấm (nhiều variant: solid, outline, ghost) |
| `<UIcon>` | Hiển thị icon (dùng bộ icon Lucide) |
| `<ULink>` | Link với styling |
| `<UBadge>` | Nhãn nhỏ (badge) |
| `<UContainer>` | Wrapper căn giữa nội dung |
| `<USeparator>` | Đường kẻ phân cách |

```vue
<!-- Ví dụ -->
<UButton color="primary" variant="solid" icon="i-lucide-send">
  Gửi
</UButton>
```

> **Lưu ý:** Nuxt UI v4 tích hợp sẵn TailwindCSS v4 — không cần cài riêng.

```bash
# Tài liệu
https://ui.nuxt.com
```

---

### `tailwindcss` — ^4.1.18
**CSS utility-first framework.** Viết CSS trực tiếp trong HTML bằng class:

```html
<!-- Thay vì viết CSS riêng -->
<div class="flex items-center gap-4 bg-white rounded-xl p-6 shadow-md">
```

Các class thường gặp:
| Class | Ý nghĩa |
|-------|---------|
| `flex`, `grid` | Layout |
| `p-4`, `mx-auto` | Padding, margin |
| `text-sm`, `font-bold` | Typography |
| `bg-white`, `text-slate-600` | Màu sắc |
| `rounded-xl`, `shadow-md` | Border radius, shadow |
| `sm:`, `md:`, `lg:` | Responsive breakpoints |
| `dark:` | Dark mode |
| `hover:`, `group-open:` | States |

---

### `@nuxt/fonts` — ^0.14.0
**Tự động tải và tối ưu font.** Không cần import Google Fonts thủ công.
- Tự phát hiện font dùng trong CSS → tải và self-host
- Giảm layout shift (CLS) khi load trang
- Hỗ trợ Google Fonts, Bunny Fonts, Adobe Fonts

---

### `@nuxt/image` — ^2.0.0
**Tối ưu hóa ảnh tự động.** Dùng `<NuxtImg>` thay `<img>`:

```vue
<!-- Tự động: lazy load, resize, chuyển WebP -->
<NuxtImg
  src="/team/member.jpg"
  width="400"
  height="400"
  loading="lazy"
  format="webp"
/>
```

Lợi ích:
- Tự chuyển sang **WebP** (nhẹ hơn ~30%)
- **Lazy loading** — chỉ tải khi user cuộn tới
- **Resize** phía server — không tải ảnh gốc 5MB

```bash
# Tài liệu
https://image.nuxt.com
```

---

### `@nuxtjs/i18n` — ^10.2.3
**Đa ngôn ngữ (Internationalization).** Hỗ trợ Tiếng Việt và English.

**Cấu trúc:**
```
i18n/
  locales/
    vi.json    ← Tiếng Việt (mặc định)
    en.json    ← English
```

**Cách dùng trong component:**
```vue
<template>
  <!-- Trong template -->
  <h1>{{ $t('hero.title') }}</h1>
</template>

<script setup>
// Trong script
const { t, locale } = useI18n()
console.log(t('hero.title'))   // "Kỷ Nguyên Số Hóa"
console.log(locale.value)      // "vi"
</script>
```

**URL routing:**
| URL | Ngôn ngữ |
|-----|----------|
| `/` | Tiếng Việt (mặc định) |
| `/en/` | English |

```bash
# Tài liệu
https://i18n.nuxtjs.org
```

---

### `@vueuse/motion` — ^3.0.3
**Thư viện animation dùng directive `v-motion`.** Tạo hiệu ứng scroll reveal cho các section.

```vue
<!-- Element mờ dần, trượt lên khi cuộn tới -->
<div
  v-motion
  :initial="{ opacity: 0, y: 30 }"
  :visible-once="{ opacity: 1, y: 0, transition: { duration: 600 } }"
>
  Nội dung sẽ hiện lên khi user cuộn tới
</div>
```

Các preset có sẵn: `visibleOnce`, `enter`, `leave`, `hover`, `tap`

```bash
# Tài liệu
https://motion.vueuse.org
```

---

### `material-icons` — ^1.13.14
**Bộ icon Material Design của Google.** Dùng trong một số component cũ.

```html
<span class="material-icons-round">forum</span>
```

> **Lưu ý:** Dự án đang chuyển dần sang dùng **Lucide icons** qua `<UIcon>` (từ Nuxt UI). Nên ưu tiên dùng Lucide cho code mới:
> ```vue
> <!-- ✅ Nên dùng -->
> <UIcon name="i-lucide-message-circle" />
>
> <!-- ⚠️ Cách cũ -->
> <span class="material-icons-round">forum</span>
> ```

---

## 🛠 DevDependencies (Gói phát triển)

### `@types/node` — ^25.0.6
**TypeScript types cho Node.js.** Cung cấp type definitions cho các API Node.js (`process`, `Buffer`, `path`...). Chỉ dùng lúc phát triển, không đi vào production.

---

## 📌 Lưu ý cho Developer mới

1. **Không cần import thủ công** — Nuxt auto-import `ref`, `computed`, `useI18n`, `useFetch`...
2. **Dùng `<NuxtLink>` thay `<a>`** — để có navigation SPA, không reload toàn trang
3. **Dùng `<NuxtImg>` thay `<img>`** — được tối ưu tự động
4. **Dùng `$t('key')` cho text** — không hardcode tiếng Việt/Anh
5. **Dùng `<UIcon name="i-lucide-xxx">` cho icon** — không dùng material-icons
6. **Dùng `useFetch()` cho API calls** — không dùng `fetch()` hay `axios`
7. **File CSS chính** nằm trong `app/assets/css/` — chia theo: `main.css`, `buttons.css`, `utilities.css`


> Tài liệu về các kỹ thuật tối ưu performance đã áp dụng và hướng dẫn cho developer.

---

## ⚡ Tổng quan các kỹ thuật đã áp dụng

| Kỹ thuật | File/Config | Tác dụng |
|----------|-------------|----------|
| SWR Cache | `nuxt.config.ts` → `routeRules` | Cache trang 1h, tự refresh khi data đổi |
| Lazy Hydration | `pages/index.vue` | Component dưới fold chỉ hydrate khi cuộn tới |
| Image Optimization | `@nuxt/image` + `<NuxtImg>` | Tự chuyển WebP, lazy load, resize |
| Font Optimization | `@nuxt/fonts` | Self-host font, giảm layout shift |
| Server-only SEO | `usePageSeo.ts` | Meta tĩnh chỉ render trên server |
| Scroll Animations | `@vueuse/motion` | Animation chỉ chạy khi element visible |

---

## 1. SWR Cache (Stale-While-Revalidate)

**File:** `nuxt.config.ts`

```ts
routeRules: {
  '/': { swr: 3600 },  // Cache 1 giờ
},
```

**Cách hoạt động:**
1. User đầu tiên → server render trang → lưu cache
2. User tiếp theo → trả cache ngay (nhanh) + server render lại phía sau
3. Sau 1h cache hết hạn → render mới

**Khi nào dùng:**
- Trang có data thay đổi không thường xuyên (trang chủ, about...)
- **Không dùng** cho trang cần real-time (giỏ hàng, dashboard)

**Thêm cho trang khác:**
```ts
routeRules: {
  '/': { swr: 3600 },
  '/about': { swr: 86400 },      // Cache 1 ngày
  '/products/**': { swr: 1800 },  // Cache 30 phút
},
```

---

## 2. Lazy Hydration

**File:** `pages/index.vue`

```vue
<template>
  <!-- Trên fold — render + hydrate ngay -->
  <HomeAppHeader />
  <HomeSectionsHero />

  <!-- Dưới fold — chỉ hydrate khi user cuộn tới -->
  <LazyHomeSectionsServices hydrate-on-visible />
  <LazyHomeSectionsProducts hydrate-on-visible />
  <LazyHomeSectionsAbout hydrate-on-visible />
</template>
```

**Cách hoạt động:**
- HTML vẫn render đầy đủ trên server (SEO OK)
- JavaScript chỉ load + hydrate khi element xuất hiện trong viewport
- Giảm JS bundle ban đầu → **trang interactive nhanh hơn**

**Quy tắc:**
| Vị trí | Cách dùng |
|--------|-----------|
| Trên fold (user thấy ngay) | `<Component />` — bình thường |
| Dưới fold (phải cuộn) | `<LazyComponent hydrate-on-visible />` |

**Đặt tên Lazy component:**
```
Component:     HomeSectionsServices
Lazy version:  LazyHomeSectionsServices   (thêm prefix "Lazy")
```

---

## 3. Image Optimization

**Module:** `@nuxt/image`

```vue
<!-- ❌ Không tối ưu -->
<img src="/team/member.jpg" />

<!-- ✅ Tối ưu -->
<NuxtImg
  src="/team/member.jpg"
  width="400"
  height="400"
  loading="lazy"
  format="webp"
  quality="80"
/>
```

**Tự động:**
- Chuyển sang **WebP** (nhẹ hơn ~30%)
- **Lazy loading** — chỉ tải khi gần viewport
- **Resize** — tải đúng kích thước cần, không tải ảnh gốc 5MB
- Thêm `width` + `height` giúp tránh **layout shift** (CLS)

---

## 4. Font Optimization

**Module:** `@nuxt/fonts`

Tự động phát hiện font dùng trong CSS → tải + self-host:
- Không gọi Google Fonts CDN → **nhanh hơn, không bị block**
- Preload font quan trọng
- Giảm **Cumulative Layout Shift** (CLS)

Font hiện tại: **Be Vietnam Pro** (định nghĩa trong `main.css`)

---

## 5. Server-only SEO Meta

**File:** `app/composables/usePageSeo.ts`

```ts
// Chỉ chạy trên server — bot Google đọc server HTML
if (import.meta.server) {
  useSeoMeta({
    ogTitle: ...,
    twitterCard: ...,
    // ... tất cả meta tĩnh
  })
}

// Title chạy cả client — user thấy trên tab
useSeoMeta({
  title: () => seoValue.value.title,
})
```

**Tại sao?** OG tags, Twitter cards, robots... chỉ cần cho bot. Bỏ trên client = **ít JS hơn**.

---

## 6. useFetch thay fetch

```ts
// ❌ Fetch thường — gọi 2 lần (server + client)
const data = await fetch('/api/products')

// ✅ useFetch — gọi 1 lần trên server, truyền data xuống client
const { data } = await useFetch('/api/products')
```

`useFetch` tự động:
- Gọi API trên **server** → truyền data qua `payload` → client dùng luôn
- **Không gọi lại** trên client = nhanh hơn, ít request hơn
- Tích hợp loading state, error handling

---

## 📊 Core Web Vitals cần quan tâm

| Metric | Ý nghĩa | Mục tiêu | Cách tối ưu |
|--------|---------|----------|-------------|
| **LCP** | Thời gian element lớn nhất hiện | < 2.5s | SWR cache, image optimize |
| **FID/INP** | Thời gian phản hồi tương tác | < 200ms | Lazy hydration, ít JS |
| **CLS** | Layout nhảy bao nhiêu | < 0.1 | Width/height cho img, font preload |

---

## ✅ Checklist Performance cho trang mới

- [ ] Dùng `<NuxtImg>` thay `<img>` (có width, height, loading="lazy")
- [ ] Component dưới fold dùng `<LazyXxx hydrate-on-visible />`
- [ ] Thêm `routeRules` SWR nếu trang không real-time
- [ ] Dùng `useFetch()` cho mọi API call
- [ ] SEO meta tĩnh wrap trong `if (import.meta.server)`
- [ ] Test bằng Chrome DevTools → Lighthouse → Performance score ≥ 90

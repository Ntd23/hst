
> Tài liệu liệt kê tất cả custom CSS class trong dự án. Copy class vào element để sử dụng.

**File CSS:** `app/assets/css/main.css` → import `utilities.css` + `buttons.css`

---

## 🎨 Design Tokens

Định nghĩa trong `main.css`:

```css
--color-primary:   #007CC3   /* Xanh dương chính */
--color-secondary: #C5A059   /* Vàng ánh kim */
```

Dùng trong Tailwind: `text-primary`, `bg-primary`, `border-secondary`...

---

## 🪟 Glass Panels

| Class | Mô tả | Dark mode |
|-------|-------|-----------|
| `glass-panel` | Nền trắng mờ 15% + blur 16px + viền trắng | ✅ tự chuyển nền tối |
| `glass-panel-darker` | Nền trắng đậm hơn 85% + blur 20px | — |
| `glass-nav` | Glass cho navigation bar | ✅ |
| `glass-nav-scrolled` | Nav khi đã scroll xuống (đậm hơn) | ✅ |

```html
<!-- Card glass -->
<div class="glass-panel rounded-xl p-6">
  Nội dung card
</div>

<!-- Navigation -->
<nav class="glass-nav glass-nav-scrolled rounded-2xl">
  ...
</nav>
```

---

## 🔘 Buttons

### Primary Buttons (nền xanh)

| Class | Hiệu ứng hover | Dùng khi |
|-------|----------------|----------|
| `btn-primary-lift` | Shimmer + glow lớn + sáng hơn | CTA chính (hero) |
| `btn-primary-lift-sm` | Shimmer + glow nhỏ + sáng hơn | CTA phụ (header, card) |
| `btn-primary-elevated` | Glow shadow (không shimmer) | Nút quan trọng (about) |

```html
<!-- Hero CTA -->
<UButton class="btn-primary-lift shadow-lg">
  Bắt đầu ngay
</UButton>

<!-- Header CTA -->
<UButton class="btn-primary-lift-sm">
  Liên hệ ngay
</UButton>

<!-- Section CTA -->
<UButton class="btn-primary-elevated">
  Tìm hiểu thêm
</UButton>
```

### Outline Button

| Class | Hiệu ứng hover |
|-------|----------------|
| `btn-outline-primary` | Nền fill từ trái sang phải, text đổi trắng |

```html
<button class="btn-outline-primary px-6 py-3 rounded-xl">
  Xem thêm
</button>
```

### Glass Button

| Class | Hiệu ứng hover |
|-------|----------------|
| `btn-glass` | Nền sáng hơn + viền primary + ring glow |

```html
<button class="btn-glass px-4 py-2 rounded-lg">
  Tùy chọn
</button>
```

### Dark Button

| Class | Hiệu ứng hover |
|-------|----------------|
| `btn-dark` | Nền đậm hơn + shimmer + shadow |

```html
<UButton class="btn-dark rounded-xl">
  Xem thêm
</UButton>
```

### Shimmer (dùng riêng)

| Class | Mô tả |
|-------|-------|
| `btn-shimmer` | Thêm hiệu ứng shimmer sweep khi hover cho bất kỳ button nào |

```html
<button class="btn-shimmer bg-secondary text-white px-6 py-3 rounded-xl">
  Hover tôi
</button>
```

### Icon Circle Buttons

| Class | Mô tả |
|-------|-------|
| `btn-icon-circle` | Base: tròn 44px, flex center |
| `btn-icon-circle-outline` | Viền xám → hover: nền primary + glow ring |
| `btn-icon-circle-primary` | Nền primary → hover: sáng hơn + glow ring |

```html
<!-- Social icon outline -->
<button class="btn-icon-circle btn-icon-circle-outline">
  <UIcon name="i-lucide-mail" />
</button>

<!-- Arrow primary -->
<button class="btn-icon-circle btn-icon-circle-primary">
  <UIcon name="i-lucide-arrow-right" />
</button>
```

---

## ✨ Card Effects

| Class | Mô tả |
|-------|-------|
| `card-hover-glow` | Hover: viền gradient animated (primary → secondary) + ambient glow |

```html
<div class="card-hover-glow glass-panel rounded-2xl p-6">
  <h3>Service Card</h3>
  <p>Mô tả dịch vụ</p>
</div>
```

---

## 🔤 Text & Visual

| Class | Mô tả |
|-------|-------|
| `text-gradient` | Text gradient xanh dương (primary → đậm hơn) |
| `bg-pastel-gradient` | Nền xanh pastel nhạt |
| `icon-glow` | Drop shadow vàng nhẹ cho icon |

```html
<h2 class="text-gradient text-3xl font-bold">
  Tiêu đề nổi bật
</h2>

<span class="material-icons-round icon-glow">star</span>
```

Ngoài ra dùng Tailwind class có sẵn:
```html
<!-- Text gradient tùy chỉnh -->
<span class="text-gradient-light">...</span>  <!-- Đã define trong component -->
```

---

## 🔗 Navigation & Links

| Class | Mô tả |
|-------|-------|
| `nav-link` | Link nav: hover đổi primary + underline gradient mở rộng từ giữa |
| `nav-muted` | Text xám nhạt → hover đổi primary |
| `link-slide-underline` | Underline trượt từ trái sang khi hover |
| `nav-item-mobile` | _(Dùng trong component)_ Style cho nav item trong mobile menu |

```html
<!-- Desktop nav -->
<a class="nav-link text-sm font-medium" href="#">
  Trang chủ
</a>

<!-- Footer link -->
<a class="link-slide-underline text-sm" href="#">
  Blog công nghệ
</a>

<!-- Muted icon button -->
<button class="nav-muted">
  <UIcon name="i-lucide-menu" />
</button>
```

---

## 📐 Kết hợp Classes

Các class có thể kết hợp với nhau và với Tailwind:

```html
<!-- Card đầy đủ hiệu ứng -->
<div class="glass-panel card-hover-glow rounded-2xl p-6 transition-all duration-300">
  <h3 class="text-gradient font-bold text-xl mb-2">Tiêu đề</h3>
  <p class="text-slate-600 dark:text-slate-300 mb-4">Mô tả</p>
  <UButton class="btn-primary-lift-sm rounded-xl">
    Khám phá ngay
  </UButton>
</div>

<!-- Icon button trong card -->
<div class="glass-panel rounded-xl p-4 flex items-center gap-4">
  <button class="btn-icon-circle btn-icon-circle-primary">
    <UIcon name="i-lucide-zap" />
  </button>
  <div>
    <h4 class="font-semibold">Feature</h4>
    <p class="text-sm text-slate-500">Description</p>
  </div>
</div>
```

---

## ⚠️ Lưu ý

1. **Luôn thêm `rounded-*`** — các class button/glass không có border-radius mặc định
2. **Dark mode tự động** — `glass-panel`, `nav-link`, `btn-glass` đã có `.dark` variants
3. **Không dùng `transform: translate`** — các hiệu ứng button tránh translate để không gây layout shift
4. **Kết hợp với UButton** — thêm class vào `class` prop: `<UButton class="btn-primary-lift">`

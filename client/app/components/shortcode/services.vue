<template>
  <section class="services-section relative py-12 overflow-hidden">
    <UContainer class="relative z-10">
      <!-- Header -->
      <div
        v-motion
        :initial="{ opacity: 0, y: 24 }"
        :visible-once="{ opacity: 1, y: 0, transition: { duration: 600 } }"
        class="text-center mb-12 sm:mb-18"
      >
        <div v-if="sectionData?.subtitle" class="inline-flex items-center gap-2 mb-4">
          <span class="subtitle-pill">
            <span class="subtitle-dot" />
            <span class="text-xs font-bold uppercase tracking-widest text-emerald-700" v-html="sectionData.subtitle" />
          </span>
        </div>
        <h2
          v-if="sectionData?.title"
          class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 leading-[1.15] tracking-tight"
          v-html="sectionData.title"
        />
        <p
          v-if="sectionData?.description"
          class="mt-5 text-gray-500 max-w-2xl mx-auto text-sm sm:text-[15px] leading-relaxed"
          v-html="sectionData.description"
        />
        <div v-if="sectionData?.button?.label && sectionData?.button?.url" class="mt-8">
          <UButton :to="sectionData.button.url" color="primary" variant="solid" size="lg" class="rounded-full px-8">
            {{ sectionData.button.label }}
          </UButton>
        </div>
      </div>

      <!-- ── Services Layout ── -->
      <div class="services-wrapper">

        <!-- FEATURED card - always first, full width on mobile -->
        <NuxtLink
          v-if="services[0]"
          :to="services[0].slug ? '/services/' + services[0].slug : '#'"
          v-motion
          :initial="{ opacity: 0, x: -30 }"
          :visible-once="{ opacity: 1, x: 0, transition: { duration: 600, delay: 60 } }"
          class="bento-card bento-featured group relative overflow-hidden"
        >
          <!-- Full-height image -->
          <template v-if="services[0].image">
            <NuxtImg
              :src="services[0].image"
              :alt="services[0].name"
              class="absolute inset-0 w-full h-full object-cover group-hover:scale-[1.04] transition-transform duration-700"
            />
            <!-- Scan-line tech overlay -->
            <div class="scan-line absolute inset-0 pointer-events-none" />
          </template>
          <div v-else class="absolute inset-0 hero-fallback flex items-center justify-center">
            <div class="glass-icon-lg">
              <i v-if="services[0].icon" :class="services[0].icon + ' text-4xl text-white'" />
              <UIcon v-else name="i-lucide-layers" class="size-10 text-white" />
            </div>
          </div>

          <!-- Badge -->
          <div class="absolute top-3 left-3 featured-badge">
            <UIcon name="i-lucide-star" class="size-3 text-amber-500" />
            <span class="text-xs font-bold text-amber-700">Nổi bật</span>
          </div>

          <!-- Text overlay at bottom -->
          <div class="featured-overlay absolute inset-x-0 bottom-0 p-4 sm:p-5 lg:p-6">
            <h3
              class="text-base sm:text-lg lg:text-xl font-extrabold text-white mb-1.5 line-clamp-2"
              v-html="services[0].name"
            />
            <p
              class="text-white/75 text-xs sm:text-sm leading-relaxed line-clamp-2 mb-3"
              v-html="services[0].description"
            />
            <span class="cta-pill">
              Khám phá ngay
              <UIcon name="i-lucide-arrow-right" class="size-3.5 group-hover:translate-x-1 transition-transform duration-200" />
            </span>
          </div>
        </NuxtLink>

        <!-- Remaining cards: horizontal scroll on mobile, grid items on desktop -->
        <div class="cards-row">
          <NuxtLink
            v-for="(service, i) in services.slice(1)"
            :key="service.name"
            :to="service.slug ? '/services/' + service.slug : '#'"
            v-motion
            :initial="sliderInitial(Number(i))"
            :visible-once="{ opacity: 1, x: 0, y: 0, transition: { duration: 550, delay: 80 * Number(i), ease: [0.22, 1, 0.36, 1] } }"
            class="bento-card slider-card group flex flex-col"
          >
            <!-- Thumbnail -->
            <div class="card-thumb relative overflow-hidden rounded-xl mb-4">
              <NuxtImg
                v-if="service.image"
                :src="service.image"
                :alt="service.name"
                class="w-full h-full object-cover group-hover:scale-[1.06] transition-transform duration-500"
              />
              <div v-else class="w-full h-full thumb-fallback flex items-center justify-center">
                <div class="glass-icon-sm">
                  <i v-if="service.icon" :class="service.icon + ' text-xl text-white'" />
                  <UIcon v-else name="i-lucide-layers" class="size-5 text-white" />
                </div>
              </div>
              <div class="card-thumb-overlay absolute inset-0" />
            </div>

            <h3
              class="text-sm sm:text-base font-bold text-gray-900 group-hover:text-emerald-600 transition-colors duration-200 mb-1.5 line-clamp-2"
              v-html="service.name"
            />
            <p
              class="text-gray-400 text-xs sm:text-sm leading-relaxed line-clamp-3 flex-1"
              v-html="service.description"
            />
            <div class="mt-3 sm:mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600">
              Xem chi tiết
              <UIcon name="i-lucide-arrow-right" class="size-3 group-hover:translate-x-1 transition-transform duration-200" />
            </div>
          </NuxtLink>
        </div>

      </div>
    </UContainer>
  </section>
</template>

<script setup lang="ts">
const props = defineProps<{
  data?: any
}>()

const sectionData = computed(() => props.data?.shortcode || {})
const services = computed(() => props.data?.services || [])

// Breakpoint detection (SSR-safe)
const isMobile = ref(true)
onMounted(() => {
  const mq = window.matchMedia('(min-width: 1024px)')
  isMobile.value = !mq.matches
  mq.addEventListener('change', (e) => { isMobile.value = !e.matches })
})

// Featured: cinematic scale + blur reveal (always)
const featuredInitial = { opacity: 0, scale: 0.93, y: 16, filter: 'blur(6px)' }

// Slider cards:
// - Mobile: slide from right (matches scroll direction)
// - Desktop: stagger slide up
const sliderInitial = (i: number) =>
  isMobile.value
    ? { opacity: 0, x: 60 + i * 20 }
    : { opacity: 0, y: 50 }
</script>

<style scoped>

/* ── Subtitle pill ── */
.subtitle-pill {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 4px 14px 4px 10px;
  background: linear-gradient(135deg, #d1fae5, #e0f2fe);
  border: 1px solid #a7f3d0;
  border-radius: 999px;
}
.subtitle-dot {
  width: 6px; height: 6px;
  border-radius: 50%;
  background: linear-gradient(135deg, #10b981, #3b82f6);
  flex-shrink: 0;
  animation: pulse-dot 2s ease-in-out infinite;
}
@keyframes pulse-dot {
  0%, 100% { transform: scale(1); opacity: 1; }
  50% { transform: scale(1.4); opacity: 0.7; }
}

/* ── Services Layout ── */
/* Mobile: full-width featured + horizontal scroll row below */
.services-wrapper {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

/* Horizontal scroll row for remaining cards on mobile */
.cards-row {
  display: flex;
  gap: 1rem;
  overflow-x: auto;
  scroll-snap-type: x mandatory;
  -webkit-overflow-scrolling: touch;
  padding-bottom: 0.625rem; /* space for scrollbar area */
  /* Peek effect: bleed past container edges */
  margin-left: -1rem;
  margin-right: -1rem;
  padding-left: 1rem;
  padding-right: 1rem;
  scrollbar-width: none;
}
.cards-row::-webkit-scrollbar { display: none; }

/* Each card in slider: fixed width so they don't shrink */
.slider-card {
  flex: 0 0 72%;
  scroll-snap-align: start;
}
@media (min-width: 480px) {
  .slider-card { flex: 0 0 55%; }
}
@media (min-width: 640px) {
  .slider-card { flex: 0 0 calc(50% - 0.5rem); }
}

/* Desktop (lg+): switch to bento grid */
@media (min-width: 1024px) {
  .services-wrapper {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
  }
  .bento-featured {
    grid-column: 1 / 3;
    grid-row: 1 / 3;
  }
  /* display:contents makes the div "transparent" so cards join the grid directly */
  .cards-row {
    display: contents;
    overflow: unset;
    margin: 0;
    padding: 0;
  }
  .slider-card {
    flex: unset;
  }
}

/* ── Cards ── */
.bento-card {
  display: flex;
  flex-direction: column;
  background: rgba(255, 255, 255, 0.75);
  backdrop-filter: blur(18px) saturate(180%);
  -webkit-backdrop-filter: blur(18px) saturate(180%);
  border: 1px solid rgba(255, 255, 255, 0.9);
  border-radius: 1.375rem;
  padding: 1.125rem;
  transition: box-shadow 0.3s ease, transform 0.3s ease, border-color 0.3s ease;
  box-shadow: 0 2px 12px rgba(0,0,0,0.06), inset 0 1px 0 rgba(255,255,255,0.8);
}
.bento-card:hover {
  transform: translateY(-5px);
  box-shadow:
    0 24px 50px -10px rgba(16, 185, 129, 0.2),
    0 8px 20px rgba(0, 0, 0, 0.08),
    inset 0 1px 0 rgba(255,255,255,0.9);
  border-color: rgba(110, 231, 183, 0.6);
}

@media (min-width: 640px) {
  .bento-card { padding: 1.375rem; }
}
@media (min-width: 1024px) {
  .bento-featured { padding: 1.75rem; }
}

/* ── Featured card: full-height image + overlay ── */
.bento-featured {
  padding: 0 !important;
  min-height: 320px;
  /* Image fills the card entirely — no glass blur needed */
  backdrop-filter: none !important;
  -webkit-backdrop-filter: none !important;
  background: transparent !important;
}
@media (min-width: 640px) { .bento-featured { min-height: 380px; } }
@media (min-width: 1024px) { .bento-featured { min-height: unset; height: 100%; } }

.hero-fallback {
  background: linear-gradient(135deg, #d1fae5 0%, #bfdbfe 100%);
}

.featured-overlay {
  background: linear-gradient(to top, rgba(0,0,0,0.72) 0%, rgba(0,0,0,0.35) 60%, transparent 100%);
}

/* ── Scan-line: tech glare sweeping down the featured image ── */
.scan-line {
  background: linear-gradient(
    to bottom,
    transparent 0%,
    rgba(255,255,255,0.04) 48%,
    rgba(255,255,255,0.12) 50%,
    rgba(255,255,255,0.04) 52%,
    transparent 100%
  );
  background-size: 100% 200%;
  animation: scan 4s linear infinite;
  mix-blend-mode: overlay;
}
@keyframes scan {
  0%   { background-position: 0% -100%; }
  100% { background-position: 0% 200%; }
}

/* ── Shimmer: plays once on card entry ── */
.bento-featured::after {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(
    105deg,
    transparent 30%,
    rgba(255,255,255,0.18) 50%,
    transparent 70%
  );
  background-size: 200% 100%;
  animation: shimmer-once 1s ease-out 0.5s both;
  pointer-events: none;
  border-radius: inherit;
  z-index: 2;
}
@keyframes shimmer-once {
  0%   { background-position: 200% 0; opacity: 0; }
  20%  { opacity: 1; }
  100% { background-position: -200% 0; opacity: 0; }
}

/* ── Slider card: subtle shimmer on enter ── */
.slider-card::after {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(
    105deg,
    transparent 30%,
    rgba(255,255,255,0.14) 50%,
    transparent 70%
  );
  background-size: 200% 100%;
  border-radius: inherit;
  pointer-events: none;
  animation: shimmer-once 0.8s ease-out 0.2s both;
  z-index: 2;
}

/* ── Card thumbnail ── */
.card-thumb {
  width: 100%;
  aspect-ratio: 4/3;
  background: linear-gradient(135deg, #ecfdf5 0%, #dbeafe 100%);
}
.thumb-fallback {
  background: linear-gradient(135deg, #d1fae5 0%, #c7d2fe 100%);
}
.card-thumb-overlay {
  background: linear-gradient(to top, rgba(4, 120, 87, 0.15), transparent);
  opacity: 0;
  transition: opacity 0.35s ease;
}
.group:hover .card-thumb-overlay { opacity: 1; }

/* ── Glass icons ── */
.glass-icon-lg {
  width: 80px; height: 80px;
  border-radius: 1.25rem;
  background: rgba(255, 255, 255, 0.28);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255,255,255,0.5);
  display: flex; align-items: center; justify-content: center;
  box-shadow: 0 8px 24px rgba(16, 185, 129, 0.25);
}
.glass-icon-sm {
  width: 48px; height: 48px;
  border-radius: 0.875rem;
  background: rgba(255, 255, 255, 0.28);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255,255,255,0.5);
  display: flex; align-items: center; justify-content: center;
  box-shadow: 0 4px 16px rgba(16, 185, 129, 0.2);
}

/* ── Number badge ── */
.num-badge {
  font-size: 0.65rem;
  font-weight: 800;
  line-height: 1;
  padding: 4px 7px;
  border-radius: 999px;
  background: rgba(255, 255, 255, 0.82);
  backdrop-filter: blur(6px);
  border: 1px solid rgba(255,255,255,0.7);
  color: #059669;
  letter-spacing: 0.03em;
}

/* ── Featured badge ── */
.featured-badge {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  padding: 4px 10px;
  background: rgba(255, 255, 255, 0.88);
  backdrop-filter: blur(8px);
  border: 1px solid rgba(253, 230, 138, 0.7);
  border-radius: 999px;
}

/* ── CTA pill ── */
.cta-pill {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 7px 16px;
  font-size: 0.8125rem;
  font-weight: 700;
  color: #059669;
  background: linear-gradient(135deg, #d1fae5, #dbeafe);
  border: 1px solid #a7f3d0;
  border-radius: 999px;
  transition: background 0.25s ease, box-shadow 0.25s ease;
}
.group:hover .cta-pill {
  background: linear-gradient(135deg, #10b981, #3b82f6);
  color: white;
  border-color: transparent;
  box-shadow: 0 4px 16px rgba(16, 185, 129, 0.4);
}
</style>

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
          class="bento-card bento-featured group relative overflow-hidden h-[320px] lg:h-auto"
        >
          <!-- Full-height image filling the entire box -->
          <template v-if="services[0].image">
            <NuxtImg
              :src="services[0].image"
              :alt="services[0].name"
              class="absolute inset-0 w-full h-full object-cover group-hover:scale-[1.05] transition-transform duration-1000"
            />
            <div class="card-overlay absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-900/40 to-transparent" />
          </template>
          <div v-else class="absolute inset-0 hero-fallback flex items-center justify-center">
            <div class="glass-icon-lg">
              <i v-if="services[0].icon" :class="services[0].icon + ' text-3xl text-white'" />
              <UIcon v-else name="i-lucide-layers" class="size-8 text-white" />
            </div>
          </div>

          <!-- Badge -->
          <div class="absolute top-4 left-4 featured-badge z-10">
            <UIcon name="i-lucide-award" class="size-3 text-amber-500" />
            <span class="text-[9px] uppercase font-black tracking-widest text-amber-700">Premium</span>
          </div>

          <!-- Text overlay at bottom -->
          <div class="absolute inset-x-0 bottom-0 p-5 sm:p-6 z-10">
            <h3
              class="text-lg sm:text-xl font-black text-white mb-2 tracking-tight leading-tight line-clamp-2"
              v-html="services[0].name"
            />
            <p
              class="text-white/80 text-xs sm:text-sm leading-relaxed line-clamp-2 mb-4 font-medium max-w-lg"
              v-html="services[0].description"
            />
            <span class="cta-pill">
              Khám phá ngay
              <UIcon name="i-lucide-arrow-right" class="size-3.5 group-hover:translate-x-1 transition-transform duration-300" />
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
            class="bento-card slider-card group relative overflow-hidden h-[260px] lg:h-[320px]"
          >
            <!-- Background Image filling the entire box -->
            <template v-if="service.image">
              <NuxtImg
                :src="service.image"
                :alt="service.name"
                class="absolute inset-0 w-full h-full object-cover group-hover:scale-[1.1] transition-transform duration-1000"
              />
              <div class="card-overlay absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-900/30 to-transparent" />
            </template>
            <div v-else class="absolute inset-0 thumb-fallback flex items-center justify-center">
              <div class="glass-icon-sm">
                <i v-if="service.icon" :class="service.icon + ' text-xl text-white'" />
                <UIcon name="i-lucide-scan" v-else class="size-5 text-white" />
              </div>
            </div>

            <!-- Content Overlay at bottom -->
            <div class="absolute inset-x-0 bottom-0 p-4 sm:p-5 z-10">
              <h3
                class="text-base font-bold text-white group-hover:text-emerald-300 transition-colors duration-300 mb-1.5 leading-tight line-clamp-2"
                v-html="service.name"
              />
              <p
                class="text-white/70 text-[11px] sm:text-xs leading-relaxed line-clamp-2 mb-3 font-medium opacity-90"
                v-html="service.description"
              />
              <div class="inline-flex items-center gap-2 text-[10px] font-black text-emerald-400 uppercase tracking-widest mt-auto">
                Details
                <UIcon name="i-lucide-arrow-right" class="size-3 group-hover:translate-x-1 transition-transform duration-300" />
              </div>
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

const rootData = computed(() => props.data?.content || props.data || {})
// If the backend puts title/subtitle inside a 'data' key, use it; otherwise use rootData itself
const sectionData = computed(() => rootData.value?.data || rootData.value || {})
const services = computed(() => rootData.value?.services || rootData.value?.items || [])

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
  position: relative;
  display: flex;
  flex-direction: column;
  background: #f8fafc;
  border-radius: 1.5rem;
  padding: 0 !important; /* Image fills the entire card */
  transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
  box-shadow: 0 4px 15px rgba(0,0,0,0.04);
  border: 1px solid rgba(226, 232, 240, 0.8);
}
.bento-card:hover {
  transform: translateY(-8px);
  box-shadow:
    0 32px 64px -12px rgba(16, 185, 129, 0.2),
    0 10px 30px rgba(0, 0, 0, 0.08);
  border-color: rgba(16, 185, 129, 0.25);
}

.bento-featured {
  min-height: 400px;
  background: transparent !important;
  backdrop-filter: none !important;
  -webkit-backdrop-filter: none !important;
}
@media (min-width: 1024px) { 
  .bento-featured { height: 100%; min-height: 520px; } 
}

.hero-fallback, .thumb-fallback {
  width: 100%; height: 100%;
  background: linear-gradient(135deg, #f0fdf4 0%, #eff6ff 100%);
}

.card-overlay {
  z-index: 1;
}

/* ── Shimmer on Card ── */
.bento-card::after {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(
    115deg,
    transparent 30%,
    rgba(255,255,255,0.12) 50%,
    transparent 70%
  );
  background-size: 200% 100%;
  animation: shimmer-once 1.2s ease-out 0.2s both;
  pointer-events: none;
  border-radius: inherit;
  z-index: 2;
}
@keyframes shimmer-once {
  0%   { background-position: 200% 0; opacity: 0; }
  20%  { opacity: 1; }
  100% { background-position: -200% 0; opacity: 0; }
}

/* ── Glass icons (Fallbacks) ── */
.glass-icon-lg {
  width: 80px; height: 80px;
  border-radius: 1.5rem;
  background: rgba(255, 255, 255, 0.4);
  backdrop-filter: blur(12px);
  border: 1px solid rgba(255,255,255,0.6);
  display: flex; align-items: center; justify-content: center;
  box-shadow: 0 10px 30px rgba(16, 185, 129, 0.1);
}
.glass-icon-sm {
  width: 56px; height: 56px;
  border-radius: 1.25rem;
  background: rgba(255, 255, 255, 0.4);
  backdrop-filter: blur(12px);
  border: 1px solid rgba(255,255,255,0.6);
  display: flex; align-items: center; justify-content: center;
}

/* ── CTA pill ── */
.cta-pill {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 8px 20px;
  font-size: 0.875rem;
  font-weight: 800;
  color: #065f46;
  background: white;
  border-radius: 999px;
  transition: all 0.3s ease;
  box-shadow: 0 10px 20px rgba(0,0,0,0.15);
}
.group:hover .cta-pill {
  background: linear-gradient(135deg, #10b981, #3b82f6);
  color: white;
  box-shadow: 0 12px 24px rgba(16, 185, 129, 0.4);
}

/* ── Featured badge ── */
.featured-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 14px;
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(8px);
  border: 1px solid white;
  border-radius: 999px;
  box-shadow: 0 8px 20px rgba(0,0,0,0.06);
}
</style>

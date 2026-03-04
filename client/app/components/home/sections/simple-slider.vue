<template>
  <header class="relative h-screen min-h-[560px] max-h-[960px] overflow-hidden bg-slate-950">

    <!-- ====== Background images — crossfade ====== -->
    <div class="absolute inset-0">
      <div
        v-for="(item, index) in sliderItems"
        :key="item.id ?? index"
        class="absolute inset-0 transition-opacity duration-[1200ms] ease-in-out"
        :class="index === activeSlide ? 'opacity-100 z-[2]' : 'opacity-0 z-[1]'"
      >
        <NuxtImg
          :src="item.image"
          :alt="item.title"
          width="1920"
          height="1080"
          sizes="100vw"
          :loading="index === 0 ? 'eager' : 'lazy'"
          :preload="index === 0"
          placeholder
          class="w-full h-full object-cover"
          :class="index === activeSlide ? 'animate-slow-zoom' : ''"
        />
      </div>

      <!-- Overlays -->
      <div class="absolute inset-0 z-[3] bg-gradient-to-b from-slate-950/70 via-slate-950/45 to-slate-950/80" />
      <div class="absolute inset-0 z-[3] bg-slate-950/20" />
    </div>

    <!-- ====== Center content ====== -->
    <div class="relative z-10 h-full flex items-center justify-center px-5 sm:px-8">
      <div class="text-center max-w-3xl mx-auto">

        <!-- Accent line + subtitle -->
        <Transition name="hero-text" mode="out-in">
          <div :key="'sub-' + activeSlide" class="flex items-center justify-center gap-3 mb-5 sm:mb-6">
            <span class="h-px w-6 sm:w-8 bg-gradient-to-r from-transparent to-white/40" />
            <span class="text-[11px] sm:text-xs font-bold tracking-[0.25em] uppercase text-white/70">
              {{ currentItem?.data_count_description || '' }}
            </span>
            <span class="h-px w-6 sm:w-8 bg-gradient-to-l from-transparent to-white/40" />
          </div>
        </Transition>

        <!-- Title -->
        <Transition name="hero-text" mode="out-in">
          <h1
            :key="'t-' + activeSlide"
            class="hero-title text-[1.75rem] sm:text-[2.25rem] md:text-[3rem] lg:text-[3.75rem] font-black leading-[1.1] tracking-tight uppercase mb-4 sm:mb-5 whitespace-nowrap"
          >
            {{ currentItem?.title || 'HISOTECH' }}
          </h1>
        </Transition>

        <!-- Description -->
        <Transition name="hero-text" mode="out-in">
          <p
            v-if="currentItem?.description"
            :key="'d-' + activeSlide"
            class="text-white/55 text-sm sm:text-base md:text-lg leading-relaxed max-w-xl mx-auto mb-7 sm:mb-9 uppercase tracking-wide"
          >
            {{ currentItem.description }}
          </p>
        </Transition>

        <!-- CTA Button -->
        <Transition name="hero-text" mode="out-in">
          <div :key="'cta-' + activeSlide">
            <NuxtLink v-if="currentItem?.button_label" :to="currentItem.link || '#'">
              <button class="hero-cta-btn group">
                <span class="uppercase tracking-wider">{{ currentItem.button_label }}</span>
                <span class="hero-cta-icon">
                  <UIcon name="i-heroicons-arrow-right-20-solid" class="w-4 h-4" />
                </span>
              </button>
            </NuxtLink>
          </div>
        </Transition>
      </div>
    </div>

    <!-- ====== Bottom progress bar ====== -->
    <div v-if="sliderItems.length > 1" class="absolute bottom-0 left-0 right-0 z-20 h-[3px] bg-white/[0.06]">
      <div
        :key="'bar-' + activeSlide"
        class="h-full bg-gradient-to-r from-primary to-primary/50 origin-left animate-slide-progress"
        :style="{ animationDuration: `${slideInterval}ms` }"
      />
    </div>
  </header>
</template>

<script setup lang="ts">
const { locale } = useI18n()

const { data: heroData } = await useFetch<any>('/api/pages/home/section/simple-slider', {
  key: `simple-slider-section-${locale.value}`,
  query: computed(() => ({ locale: locale.value })),
})

const sliderItems = computed(() => heroData.value?.items ?? [])
const currentItem = computed(() => sliderItems.value[activeSlide.value] ?? null)

const activeSlide = ref(0)
const slideInterval = 6000
let timer: ReturnType<typeof setInterval> | null = null

const goToSlide = (index: number) => {
  activeSlide.value = index
  resetTimer()
}

const nextSlide = () => {
  if (sliderItems.value.length > 1)
    activeSlide.value = (activeSlide.value + 1) % sliderItems.value.length
}

const resetTimer = () => {
  if (timer) clearInterval(timer)
  timer = setInterval(nextSlide, slideInterval)
}

onMounted(() => resetTimer())
onBeforeUnmount(() => { if (timer) clearInterval(timer) })
</script>

<style scoped>
/* ===== Title ===== */
.hero-title {
  font-family: 'Monda', sans-serif;
  color: white;
  text-shadow: 0 2px 24px rgba(0, 0, 0, 0.3);
}

/* ===== CTA Button — pill with arrow circle ===== */
.hero-cta-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.875rem;
  padding: 0.5rem 0.5rem 0.5rem 2rem;
  border-radius: 9999px;
  font-size: 0.875rem;
  font-weight: 700;
  letter-spacing: 0.05em;
  color: white;
  background: linear-gradient(135deg, var(--color-primary), #0091d5);
  box-shadow:
    0 8px 32px rgba(0, 124, 195, 0.4),
    0 0 0 1px rgba(255, 255, 255, 0.08) inset;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  cursor: pointer;
  border: none;
}

.hero-cta-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 9999px;
  background: rgba(255, 255, 255, 0.2);
  transition: all 0.3s ease;
}

.hero-cta-btn:hover {
  transform: translateY(-2px);
  box-shadow:
    0 14px 40px rgba(0, 124, 195, 0.5),
    0 0 0 1px rgba(255, 255, 255, 0.12) inset;
}

.hero-cta-btn:hover .hero-cta-icon {
  background: rgba(255, 255, 255, 0.3);
  transform: translateX(3px);
}

.hero-cta-btn:active {
  transform: translateY(0);
}

/* ===== Ken Burns ===== */
.animate-slow-zoom {
  animation: slowZoom 7s ease-out forwards;
}

@keyframes slowZoom {
  from { transform: scale(1); }
  to   { transform: scale(1.06); }
}

/* ===== Progress bar ===== */
@keyframes slideProgress {
  from { transform: scaleX(0); }
  to   { transform: scaleX(1); }
}

.animate-slide-progress {
  animation: slideProgress linear forwards;
}

/* ===== Text transitions ===== */
.hero-text-enter-active {
  transition: opacity 0.45s ease, transform 0.45s cubic-bezier(0.4, 0, 0.2, 1);
}
.hero-text-leave-active {
  transition: opacity 0.2s ease, transform 0.2s ease;
}
.hero-text-enter-from {
  opacity: 0;
  transform: translateY(14px);
}
.hero-text-leave-to {
  opacity: 0;
  transform: translateY(-6px);
}
</style>

<template>
  <header class="relative h-screen min-h-[600px] max-h-[900px] overflow-hidden">
    <!-- Slideshow background images — horizontal sliding -->
    <div class="absolute inset-0">
      <div
        class="slide-track absolute inset-0 flex transition-transform duration-800 ease-[cubic-bezier(0.4,0,0.2,1)]"
        :style="{ transform: `translateX(-${activeSlide * 100}%)` }"
      >
        <div
          v-for="(slide, index) in slides"
          :key="index"
          class="relative w-full h-full shrink-0"
        >
          <img
            :src="slide.image"
            :alt="slide.alt"
            class="w-full h-full object-cover"
          />
        </div>
      </div>
      <!-- Dark overlay -->
      <div class="absolute inset-0 bg-gradient-to-b from-slate-900/60 via-slate-900/40 to-slate-900/70 z-[1]" />
      <div class="absolute inset-0 bg-gradient-to-r from-primary/10 via-transparent to-secondary/10 z-[1]" />
    </div>

    <!-- Slide indicators -->
    <div v-if="slides.length > 1" class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 flex gap-2.5">
      <button
        v-for="(_, index) in slides"
        :key="index"
        class="group relative h-2 rounded-full transition-all duration-500"
        :class="index === activeSlide ? 'w-10 bg-white' : 'w-2 bg-white/40 hover:bg-white/60'"
        @click="goToSlide(index)"
      >
        <span
          v-if="index === activeSlide"
          :key="'progress-' + activeSlide"
          class="absolute inset-0 rounded-full bg-white/50 origin-left animate-slide-progress"
          :style="{ animationDuration: `${slideInterval}ms` }"
        />
      </button>
    </div>

    <!-- Centered content overlay -->
    <div class="relative z-10 h-full flex items-center justify-center">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center space-y-6 sm:space-y-8">
        <UBadge color="neutral" variant="subtle" class="backdrop-blur-md bg-white/10 border border-white/20 text-white/90">
          <span class="text-secondary font-bold mr-1">{{ $t('hero.badgeNew') }}</span>
          {{ $t('hero.badge') }}
        </UBadge>

        <h1 class="text-4xl sm:text-5xl lg:text-7xl font-bold leading-tight text-white drop-shadow-lg">
          {{ $t('hero.title1') }} <span class="text-gradient-light">{{ $t('hero.titleHighlight') }}</span> <br />
          {{ $t('hero.title2') }}
        </h1>

        <p class="text-base sm:text-lg text-white/80 max-w-2xl mx-auto leading-relaxed drop-shadow">
          {{ $t('hero.description') }}
        </p>

        <div class="flex flex-wrap items-center justify-center gap-4 pt-2">
          <UButton
            color="primary"
            variant="solid"
            size="xl"
            class="rounded-xl font-semibold btn-primary-lift shadow-lg shadow-primary/30"
          >
            {{ $t('hero.cta') }}
          </UButton>
          <UButton
            color="neutral"
            variant="ghost"
            size="xl"
            icon="i-lucide-play-circle"
            class="rounded-xl font-semibold group text-white border border-white/25 bg-white/10 backdrop-blur-sm hover:bg-white/20"
          >
            {{ $t('hero.watchDemo') }}
          </UButton>
        </div>

        <USeparator color="neutral" class="max-w-lg mx-auto opacity-20" />

        <div class="grid grid-cols-3 gap-4 sm:gap-8 max-w-lg mx-auto">
          <div>
            <p class="text-2xl sm:text-3xl font-bold text-white">45+</p>
            <p class="text-sm text-white/60">{{ $t('hero.statSolutions') }}</p>
          </div>
          <div>
            <p class="text-2xl sm:text-3xl font-bold text-white">92K</p>
            <p class="text-sm text-white/60">{{ $t('hero.statUsers') }}</p>
          </div>
          <div>
            <p class="text-2xl sm:text-3xl font-bold text-white">25+</p>
            <p class="text-sm text-white/60">{{ $t('hero.statAwards') }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Scroll indicator -->
    <div class="absolute bottom-8 right-8 z-20 hidden lg:flex flex-col items-center gap-2 text-white/40">
      <span class="text-xs tracking-widest uppercase" style="writing-mode: vertical-rl">Scroll</span>
      <div class="w-px h-10 bg-gradient-to-b from-white/40 to-transparent animate-pulse" />
    </div>
  </header>
</template>

<script setup lang="ts">
// Slides data — will be replaced by admin API
const slides = ref([
  {
    image: 'https://images.unsplash.com/photo-1551434678-e076c223a692?w=1600&q=80',
    alt: 'Đội ngũ HISOTECH làm việc cùng nhau',
  },
  {
    image: 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=1600&q=80',
    alt: 'Văn phòng công nghệ hiện đại',
  },
  {
    image: 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=1600&q=80',
    alt: 'Không gian làm việc sáng tạo',
  },
])

const activeSlide = ref(0)
const slideInterval = 6000
let timer: ReturnType<typeof setInterval> | null = null

const goToSlide = (index: number) => {
  activeSlide.value = index
  resetTimer()
}

const nextSlide = () => {
  activeSlide.value = (activeSlide.value + 1) % slides.value.length
}

const resetTimer = () => {
  if (timer) clearInterval(timer)
  timer = setInterval(nextSlide, slideInterval)
}

onMounted(() => {
  resetTimer()
})

onBeforeUnmount(() => {
  if (timer) clearInterval(timer)
})
</script>

<style scoped>
.text-gradient-light {
  background: linear-gradient(135deg, #4fc3f7, #00bcd4, #c5a059);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.duration-800 {
  transition-duration: 800ms;
}

@keyframes slideProgress {
  from { transform: scaleX(0); }
  to { transform: scaleX(1); }
}

.animate-slide-progress {
  animation: slideProgress linear forwards;
}
</style>

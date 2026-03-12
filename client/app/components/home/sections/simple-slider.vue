<template>
  <header
    class="relative h-screen min-h-[560px] max-h-[960px] overflow-hidden bg-slate-950"
  >
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

      <!-- Base overlay -->
      <div
        class="absolute inset-0 z-[3] bg-gradient-to-b from-slate-950/50 via-slate-950/25 to-slate-950/60"
      />
    </div>

    <!-- ====== Center content ====== -->
    <div
      class="relative z-10 h-full flex items-center justify-center px-5 sm:px-8"
    >
      <div class="text-center max-w-3xl mx-auto">
        <!-- Accent line + subtitle -->
        <Transition name="hero-text" mode="out-in">
          <div
            :key="'sub-' + activeSlide"
            class="flex items-center justify-center gap-3 mb-5 sm:mb-6"
          >
            <span
              class="h-px w-6 sm:w-8 bg-gradient-to-r from-transparent to-white/40"
            />
            <span
              class="text-[11px] sm:text-xs font-bold tracking-[0.25em] uppercase text-white/70"
            >
              {{ currentItem?.data_count_description || "" }}
            </span>
            <span
              class="h-px w-6 sm:w-8 bg-gradient-to-l from-transparent to-white/40"
            />
          </div>
        </Transition>

        <!-- Title — typewriter reveal -->
        <h1
          :key="'t-' + activeSlide"
          class="hero-title text-[1.75rem] sm:text-[2.25rem] md:text-[3rem] lg:text-[3.75rem] font-black leading-[1.1] tracking-tight uppercase mb-4 sm:mb-5 whitespace-nowrap"
        >
          <span>{{ typedText }}</span
          ><span v-if="isTyping" class="typewriter-cursor">|</span>
        </h1>

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
            <NuxtLink
              v-if="currentItem?.button_label"
              :to="currentItem.link || '#'"
            >
              <button class="hero-cta-btn group">
                <span class="uppercase tracking-wider">{{
                  currentItem.button_label
                }}</span>
                <span class="hero-cta-icon">
                  <UIcon
                    name="i-heroicons-arrow-right-20-solid"
                    class="w-4 h-4"
                  />
                </span>
              </button>
            </NuxtLink>
          </div>
        </Transition>
      </div>
    </div>

    <!-- ====== Bottom progress bar ====== -->
    <div
      v-if="sliderItems.length > 1"
      class="absolute bottom-0 left-0 right-0 z-20 h-[3px] bg-white/[0.06]"
    >
      <div
        :key="'bar-' + activeSlide"
        class="h-full bg-gradient-to-r from-primary to-primary/50 origin-left animate-slide-progress"
        :style="{ animationDuration: `${slideInterval}ms` }"
      />
    </div>
  </header>
</template>

<script setup lang="ts">
const props = defineProps<{
  data?: any
}>()

const sliderData = computed(() => props.data)
const sliderItems = computed(() => sliderData.value?.items ?? [])
const currentItem = computed(() => sliderItems.value[activeSlide.value] ?? null)

const activeSlide = ref(0);
const slideInterval = 6000;
let timer: ReturnType<typeof setInterval> | null = null;

// ===== Typewriter =====
const typedText = ref("");
const isTyping = ref(false);
let typeTimer: ReturnType<typeof setTimeout> | null = null;

const typeTitle = (text: string) => {
  if (typeTimer) clearTimeout(typeTimer);
  typedText.value = "";
  isTyping.value = true;
  let i = 0;
  const speed = 45; // ms per character

  const tick = () => {
    if (i < text.length) {
      typedText.value = text.slice(0, i + 1);
      i++;
      typeTimer = setTimeout(tick, speed);
    } else {
      // Cursor blinks briefly then disappears
      setTimeout(() => {
        isTyping.value = false;
      }, 800);
    }
  };
  tick();
};

// Re-type whenever slide changes
watch(
  () => currentItem.value?.title,
  (newTitle) => {
    typeTitle(newTitle || "HISOTECH");
  },
  { immediate: true }
);

// ===== Slide navigation =====
const goToSlide = (index: number) => {
  activeSlide.value = index;
  resetTimer();
};

const nextSlide = () => {
  if (sliderItems.value.length > 1)
    activeSlide.value = (activeSlide.value + 1) % sliderItems.value.length;
};

const resetTimer = () => {
  if (timer) clearInterval(timer);
  timer = setInterval(nextSlide, slideInterval);
};

onMounted(() => {
  resetTimer();
});
onBeforeUnmount(() => {
  if (timer) clearInterval(timer);
  if (typeTimer) clearTimeout(typeTimer);
});
</script>

<style scoped>
/* ===== Title ===== */
.hero-title {
  font-family: "Monda", sans-serif;
  color: white;
  text-shadow: 0 2px 24px rgba(0, 0, 0, 0.3);
}

/* ===== CTA Button — pill with glowing border ===== */
.hero-cta-btn {
  position: relative;
  display: inline-flex;
  align-items: center;
  gap: 0.875rem;
  padding: 0.5rem 0.5rem 0.5rem 2rem;
  border-radius: 9999px;
  font-size: 0.875rem;
  font-weight: 700;
  letter-spacing: 0.05em;
  color: white;
  background: transparent;
  box-shadow: 0 8px 32px rgba(0, 124, 195, 0.4);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  cursor: pointer;
  border: none;
  overflow: hidden;
  z-index: 10;
}

.hero-cta-btn > span {
  position: relative;
  z-index: 10;
}

.hero-cta-btn::before {
  content: "";
  position: absolute;
  top: -150%;
  left: -50%;
  width: 200%;
  height: 400%;
  background: conic-gradient(
    from 0deg,
    transparent 0%,
    rgba(255, 255, 255, 0.8) 25%,
    transparent 28%,
    transparent 50%,
    rgba(255, 255, 255, 0.8) 75%,
    transparent 78%
  );
  animation: border-spin 3s linear infinite;
  z-index: -2;
}

.hero-cta-btn::after {
  content: "";
  position: absolute;
  inset: 2px;
  border-radius: 9999px;
  background: linear-gradient(135deg, var(--color-primary), #0091d5);
  box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.08) inset;
  z-index: -1;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes border-spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.hero-cta-icon {
  position: relative;
  z-index: 10;
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
  box-shadow: 0 14px 40px rgba(0, 124, 195, 0.5);
}

.hero-cta-btn:hover::after {
  box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.12) inset;
}

.hero-cta-btn:hover .hero-cta-icon {
  background: white;
  color: var(--color-primary);
  transform: translateX(3px) scale(1.05);
}

.hero-cta-btn:active {
  transform: translateY(0);
}

/* ===== Ken Burns ===== */
.animate-slow-zoom {
  animation: slowZoom 7s ease-out forwards;
}

@keyframes slowZoom {
  from {
    transform: scale(1);
  }
  to {
    transform: scale(1.06);
  }
}

/* ===== Progress bar ===== */
@keyframes slideProgress {
  from {
    transform: scaleX(0);
  }
  to {
    transform: scaleX(1);
  }
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

/* ===== Typewriter cursor ===== */
.typewriter-cursor {
  display: inline-block;
  color: var(--color-primary);
  font-weight: 300;
  animation: blink 0.6s step-end infinite;
  margin-left: 2px;
}

@keyframes blink {
  50% {
    opacity: 0;
  }
}
</style>

<template>
  <header
    v-if="!sliderItems.length"
    class="relative h-screen min-h-[560px] max-h-[960px] overflow-hidden bg-slate-950"
  >
    <div class="absolute inset-0 animate-pulse bg-gradient-to-br from-slate-300/70 via-slate-200/45 to-slate-400/65" />
    <div
      class="relative z-10 flex h-full items-center px-5 py-16 sm:px-8 sm:py-20"
    >
      <div class="mx-auto flex h-full w-full max-w-6xl items-center">
        <div class="grid w-full gap-10 lg:grid-cols-[minmax(0,1.05fr)_minmax(320px,0.8fr)] lg:items-center">
          <div class="flex flex-col justify-center">
            <div class="mb-6 h-4 w-40 rounded-full bg-white/30 sm:w-52" />
            <div class="space-y-4">
              <div class="h-14 w-full max-w-3xl rounded-[1.75rem] bg-white/55 sm:h-16 lg:h-20" />
              <div class="h-14 w-[92%] max-w-[42rem] rounded-[1.75rem] bg-white/50 sm:h-16 lg:h-20" />
            </div>
            <div class="mt-8 space-y-3">
              <div class="h-4 w-full max-w-2xl rounded-full bg-white/28" />
              <div class="h-4 w-[88%] max-w-xl rounded-full bg-white/22" />
              <div class="h-4 w-[72%] max-w-lg rounded-full bg-white/18" />
            </div>
            <div class="mt-10 flex items-center gap-4">
              <div class="h-14 w-52 rounded-full bg-white/40" />
              <div class="hidden h-14 w-14 rounded-full bg-white/22 sm:block" />
            </div>
          </div>

          <div class="hidden lg:flex lg:justify-end">
            <div class="w-full max-w-md space-y-5">
              <div class="h-72 rounded-[2rem] bg-white/18 backdrop-blur-sm xl:h-80" />
              <div class="grid grid-cols-2 gap-4">
                <div class="h-24 rounded-[1.5rem] bg-white/16" />
                <div class="h-24 rounded-[1.5rem] bg-white/12" />
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="absolute bottom-8 left-1/2 flex -translate-x-1/2 items-center gap-3 sm:bottom-10">
        <div class="h-1.5 w-16 rounded-full bg-white/45" />
        <div class="h-1.5 w-8 rounded-full bg-white/25" />
        <div class="h-1.5 w-8 rounded-full bg-white/25" />
      </div>
    </div>
  </header>

  <header
    v-else
    class="relative h-screen min-h-[560px] max-h-[960px] overflow-hidden bg-slate-950"
  >
    <!-- ====== Background images — crossfade ====== -->
    <div class="absolute inset-0">
      <div
        v-for="(item, index) in sliderItems"
        :key="item.id ?? index"
        class="hero-slide absolute inset-0 transition-opacity duration-[1200ms] ease-in-out"
        :class="index === activeSlide ? 'opacity-100 z-[2]' : 'opacity-0 z-[1]'"
        :style="{ backgroundImage: toBackgroundImage(item.image) }"
        role="img"
        :aria-label="item.title || ''"
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
                    <CommonsBotbleIcon
                      icon="i-heroicons-arrow-right-20-solid"
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
import CommonsBotbleIcon from "~/components/commons/BotbleIcon.vue";
const props = defineProps<{
  data?: any
}>()

const {
  sliderItems,
  currentItem,
  activeSlide,
  typedText,
  isTyping,
  slideInterval,
} = useSimpleSliderShortcode(toRef(props, "data"))

const toBackgroundImage = (source?: string | null) =>
  source ? `url(${JSON.stringify(source)})` : "none";
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

/* Use a CSS background instead of a transformed <img>. Safari/WebKit can
   collapse an object-fit image into a thin horizontal compositing layer. */
.hero-slide {
  width: 100%;
  height: 100%;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
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

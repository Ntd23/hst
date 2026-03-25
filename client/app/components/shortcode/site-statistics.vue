<template>
  <section ref="sectionRef" class="stats-section relative py-6 overflow-hidden">
    <UContainer class="relative z-10">
      <!-- Header -->
      <div
        v-if="sectionData?.title || sectionData?.subtitle"
        v-motion
        :initial="{ opacity: 0, y: 24 }"
        :visible-once="{ opacity: 1, y: 0, transition: { duration: 600 } }"
        class="text-center mb-12 sm:mb-16"
      >
        <div v-if="sectionData?.subtitle" class="inline-flex items-center gap-2 mb-3">
          <span class="h-px w-6 bg-primary/50" />
          <span class="text-primary font-bold tracking-widest uppercase text-xs">{{ sectionData.subtitle }}</span>
          <span class="h-px w-6 bg-primary/50" />
        </div>
        <h2 v-if="sectionData?.title" class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-gray-900 tracking-tight">
          {{ sectionData.title }}
        </h2>
        <p v-if="sectionData?.description" class="mt-4 text-gray-500 max-w-xl mx-auto text-sm sm:text-base leading-relaxed">
          {{ sectionData.description }}
        </p>
      </div>

      <!-- Stats Bento Grid: 2 cols mobile → 4 cols desktop -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 lg:gap-6">
        <div
          v-for="(tab, index) in tabs"
          :key="index"
          v-motion
          :initial="{ opacity: 0, y: 40, scale: 0.95 }"
          :visible-once="{ opacity: 1, y: 0, scale: 1, transition: { duration: 550, delay: index * 90, ease: [0.16, 1, 0.3, 1] } }"
          class="stat-card group"
        >
          <!-- 3D Icon container -->
          <div class="icon-3d-wrap mb-4 sm:mb-5">
            <div class="icon-3d-inner">
              <NuxtImg
                v-if="tab.image"
                :src="tab.image"
                :alt="tab.title"
                width="48"
                height="48"
                loading="lazy"
                class="w-9 h-9 sm:w-11 sm:h-11 object-contain drop-shadow-md group-hover:-translate-y-1 group-hover:scale-110 transition-transform duration-500"
              />
              <UIcon v-else name="i-lucide-bar-chart-2" class="size-9 sm:size-11 text-white drop-shadow" />
            </div>
          </div>

          <!-- Number + Unit -->
          <div class="flex items-baseline justify-center gap-0.5">
            <span class="stat-number text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight tabular-nums">
              {{ animatedValues[index] ?? 0 }}
            </span>
            <span v-if="tab.unit" class="stat-unit text-lg sm:text-xl lg:text-2xl font-black">
              {{ tab.unit }}
            </span>
          </div>

          <!-- Divider -->
          <div class="stat-divider my-3 sm:my-4" />

          <!-- Label -->
          <p class="text-gray-500 text-xs sm:text-sm font-medium text-center leading-snug line-clamp-2">
            {{ tab.title }}
          </p>
        </div>
      </div>

      <!-- CTA -->
      <div
        v-if="sectionData?.button?.label && sectionData?.button?.url"
        v-motion
        :initial="{ opacity: 0, y: 20 }"
        :visible-once="{ opacity: 1, y: 0, transition: { duration: 600, delay: 500 } }"
        class="text-center mt-12 sm:mt-16"
      >
        <UButton
          :to="sectionData.button.url"
          color="primary"
          variant="solid"
          size="lg"
          trailing-icon="i-lucide-arrow-right"
          class="rounded-full px-8 font-bold shadow-lg shadow-primary/25"
        >
          {{ sectionData.button.label }}
        </UButton>
      </div>
    </UContainer>
  </section>
</template>

<script setup lang="ts">
const props = defineProps<{
  data?: any
}>()

const rootData = computed(() => props.data?.content || props.data || {})
const sectionData = computed(() => rootData.value?.data || rootData.value || {})
const tabs = computed<any[]>(() => sectionData.value?.tabs || sectionData.value?.items || [])

// ===== Count-up animation =====
const sectionRef = ref<HTMLElement | null>(null)
const animatedValues = ref<number[]>([])
let hasAnimated = false

watch(tabs, (newTabs) => {
  animatedValues.value = newTabs.map(() => 0)
}, { immediate: true })

const animateCountUp = () => {
  if (hasAnimated) return
  hasAnimated = true

  tabs.value.forEach((tab, index) => {
    const target = parseInt(tab.data) || 0
    const duration = 2000
    const steps = 60
    const stepDuration = duration / steps
    let current = 0
    const increment = target / steps

    const timer = setInterval(() => {
      current += increment
      if (current >= target) {
        current = target
        clearInterval(timer)
      }
      animatedValues.value[index] = Math.round(current)
    }, stepDuration)
  })
}

onMounted(() => {
  if (!sectionRef.value) return
  const observer = new IntersectionObserver(
    (entries) => {
      if (entries[0]?.isIntersecting) {
        animateCountUp()
        observer.disconnect()
      }
    },
    { threshold: 0.3 },
  )
  observer.observe(sectionRef.value)
})
</script>

<style scoped>

/* ── Stat card: glassmorphism white card ── */
.stat-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 1.25rem 1rem;
  border-radius: 1.5rem;
  background: rgba(255, 255, 255, 0.82);
  backdrop-filter: blur(16px) saturate(160%);
  -webkit-backdrop-filter: blur(16px) saturate(160%);
  border: 1px solid rgba(255, 255, 255, 0.95);
  box-shadow:
    0 2px 16px rgba(0, 0, 0, 0.05),
    0 0 0 1px rgba(200, 230, 255, 0.3),
    inset 0 1px 0 rgba(255, 255, 255, 1);
  transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.35s ease;
}
.stat-card:hover {
  transform: translateY(-6px);
  box-shadow:
    0 20px 40px -8px rgba(14, 165, 233, 0.18),
    0 0 0 1.5px rgba(125, 211, 252, 0.5),
    inset 0 1px 0 rgba(255, 255, 255, 1);
}

@media (min-width: 640px) {
  .stat-card { padding: 1.75rem 1.25rem; }
}

/* ── 3D Icon wrapper ── */
.icon-3d-wrap {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
}
.icon-3d-inner {
  width: 3.5rem;
  height: 3.5rem;
  border-radius: 1.125rem;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(145deg, #38bdf8, #6366f1);
  box-shadow:
    0 8px 20px rgba(99, 102, 241, 0.35),
    0 2px 6px rgba(0,0,0,0.12),
    inset 0 1px 0 rgba(255,255,255,0.35),
    inset 0 -2px 4px rgba(0,0,0,0.12);
  transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s ease;
}
.stat-card:hover .icon-3d-inner {
  transform: translateY(-4px) rotate(-4deg);
  box-shadow:
    0 16px 32px rgba(99, 102, 241, 0.4),
    0 4px 8px rgba(0,0,0,0.12),
    inset 0 1px 0 rgba(255,255,255,0.4);
}

@media (min-width: 640px) {
  .icon-3d-inner { width: 4rem; height: 4rem; border-radius: 1.25rem; }
}

/* ── Tech number typography ── */
.stat-number {
  font-family: var(--font-tech, 'Monda', sans-serif);
  background: linear-gradient(355deg, #0f172a 0%, #0ea5e9 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  letter-spacing: -0.03em;
  line-height: 1;
}
.stat-unit {
  font-family: var(--font-tech, 'Monda', sans-serif);
  background: linear-gradient(135deg, #0ea5e9, #6366f1);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  letter-spacing: -0.02em;
  line-height: 1;
}

/* ── Divider with gradient ── */
.stat-divider {
  width: 2.5rem;
  height: 2px;
  border-radius: 999px;
  background: linear-gradient(90deg, #38bdf8, #818cf8);
  opacity: 0.5;
  transition: width 0.3s ease, opacity 0.3s ease;
}
.stat-card:hover .stat-divider {
  width: 3.5rem;
  opacity: 0.9;
}
</style>

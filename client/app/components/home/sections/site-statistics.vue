<template>
  <section
    ref="sectionRef"
    class="stats-section relative py-10 overflow-hidden"
  >
    <UContainer class="relative z-10">
      <!-- Section header -->
      <div
        v-if="sectionData?.title || sectionData?.subtitle"
        v-motion
        :initial="{ opacity: 0, y: 30 }"
        :visible-once="{ opacity: 1, y: 0, transition: { duration: 600 } }"
        class="text-center mb-12 sm:mb-16"
      >
        <span v-if="sectionData?.subtitle" class="text-primary font-semibold tracking-wide uppercase text-sm">
          {{ sectionData.subtitle }}
        </span>
        <h2 v-if="sectionData?.title" class="mt-2 text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-900">
          {{ sectionData.title }}
        </h2>
        <p v-if="sectionData?.description" class="mt-4 text-slate-600 max-w-2xl mx-auto leading-relaxed">
          {{ sectionData.description }}
        </p>
      </div>

      <!-- Stats grid -->
      <div class="grid grid-cols-4 gap-2 sm:gap-6 lg:gap-8 max-w-7xl mx-auto">
        <div
          v-for="(tab, index) in tabs"
          :key="index"
          v-motion
          :initial="{ opacity: 0, y: 30 }"
          :visible-once="{ opacity: 1, y: 0, transition: { duration: 500, delay: index * 100 } }"
          class="stat-item flex flex-col items-center group relative p-1 sm:p-2"
        >
          <!-- Icon -->
          <div class="stat-icon-wrapper shrink-0">
            <NuxtImg
              v-if="tab.image"
              :src="tab.image"
              :alt="tab.title"
              width="40"
              height="40"
              loading="lazy"
              class="w-8 h-8 sm:w-10 sm:h-10 object-contain transition-transform duration-500 group-hover:scale-110 group-hover:-rotate-3"
            />
          </div>

          <!-- Number + Unit -->
          <div class="flex items-baseline justify-center gap-[2px] mt-3 sm:mt-5">
            <span class="stat-number text-2xl sm:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight tabular-nums">
              {{ animatedValues[index] ?? 0 }}
            </span>
            <span v-if="tab.unit" class="text-lg sm:text-xl lg:text-2xl font-bold text-primary">
              {{ tab.unit }}
            </span>
          </div>

          <!-- Title -->
          <p class="mt-1.5 sm:mt-3 text-[10px] sm:text-sm lg:text-base text-slate-500 font-medium text-center leading-snug sm:leading-relaxed text-balance line-clamp-3">
            {{ tab.title }}
          </p>
        </div>
      </div>

      <!-- CTA Button -->
      <div
        v-if="sectionData?.button?.label && sectionData?.button?.url"
        v-motion
        :initial="{ opacity: 0, y: 20 }"
        :visible-once="{ opacity: 1, y: 0, transition: { duration: 600, delay: 600 } }"
        class="text-center mt-12 sm:mt-16"
      >
        <UButton
          :to="sectionData.button.url"
          color="primary"
          variant="solid"
          size="lg"
          trailing-icon="i-lucide-arrow-right"
          class="rounded-xl font-semibold btn-primary-elevated"
        >
          {{ sectionData.button.label }}
        </UButton>
      </div>
    </UContainer>
  </section>
</template>

<script setup lang="ts">
const { locale } = useI18n()

const { data: rawData } = await usePageSection<any>('home', 'site-statistics')

const sectionData = computed(() => rawData.value?.data || rawData.value || null)
const tabs = computed<any[]>(() => sectionData.value?.tabs ?? [])

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
.stat-item {
  transition: all 0.3s ease;
}

.stat-icon-wrapper {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 3.5rem;
  height: 3.5rem;
  border-radius: 1rem;
  background: linear-gradient(135deg, rgba(var(--color-primary-rgb), 0.1), rgba(var(--color-primary-rgb), 0.05));
  transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

@media (min-width: 640px) {
  .stat-icon-wrapper {
    width: 4rem;
    height: 4rem;
    border-radius: 1.25rem;
    background: linear-gradient(135deg, var(--color-primary), rgba(0, 124, 195, 0.8));
    box-shadow: 0 4px 16px rgba(0, 124, 195, 0.2);
  }
}

.stat-item:hover .stat-icon-wrapper {
  transform: translateY(-4px);
}

.stat-number {
  font-family: 'Monda', 'Inter', sans-serif;
  background: linear-gradient(to right, #0f172a, var(--color-primary));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
</style>

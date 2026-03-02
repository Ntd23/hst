<template>
  <section class="relative py-16">
    <UContainer>
      <div
        v-motion
        :initial="{ opacity: 0, y: 30 }"
        :visible-once="{ opacity: 1, y: 0, transition: { duration: 600 } }"
        class="text-center mb-10 sm:mb-16"
      >
        <span class="text-secondary font-semibold tracking-wide uppercase text-sm">{{ $t('services.subtitle') }}</span>
        <h2 class="mt-2 text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">
          {{ $t('services.title') }}
        </h2>
      </div>

      <!-- Bento Grid: phone=1col, tablet=2col, desktop=5col bento -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5 sm:gap-6">
        <!-- Featured (first item) -->
        <UCard
          v-if="services.length"
          v-motion
          :initial="{ opacity: 0, x: -40 }"
          :visible-once="{ opacity: 1, x: 0, transition: { duration: 600, delay: 100 } }"
          class="card-hover-glow group transition-all duration-300 relative overflow-hidden md:col-span-2 lg:col-span-3 lg:row-span-2"
        >
          <div class="flex flex-col h-full">
            <div class="flex items-start justify-between mb-4 sm:mb-6">
              <div
                class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-primary/10 text-primary flex items-center justify-center shadow-sm"
              >
                <UIcon :name="services[0].icon" class="size-7 sm:size-8" />
              </div>
              <UBadge color="primary" variant="subtle" size="sm">Nổi bật</UBadge>
            </div>
            <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-slate-900 dark:text-white mb-2 sm:mb-3">
              {{ services[0].title }}
            </h3>
            <p class="text-slate-600 dark:text-slate-400 text-sm sm:text-base leading-relaxed mb-4 sm:mb-6 flex-1">
              {{ services[0].description }}
            </p>
            <ULink
              class="inline-flex items-center text-sm font-semibold text-primary hover:text-secondary transition-colors"
              to="#"
            >
              Khám phá ngay
              <UIcon name="i-lucide-arrow-right" class="size-4 ml-1" />
            </ULink>
          </div>
          <div class="absolute -bottom-8 -right-8 opacity-[0.05] group-hover:opacity-[0.1] transition-opacity">
            <UIcon :name="services[0].bgIcon" class="size-32 sm:size-40 lg:size-52 text-primary" />
          </div>
        </UCard>

        <!-- Remaining items -->
        <UCard
          v-for="(service, i) in services.slice(1)"
          :key="service.title"
          v-motion
          :initial="{ opacity: 0, y: 30 }"
          :visible-once="{ opacity: 1, y: 0, transition: { duration: 600, delay: 150 + i * 120 } }"
          class="card-hover-glow group transition-all duration-300 relative overflow-hidden lg:col-span-2"
        >
          <div class="flex items-start gap-4">
            <div
              class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0"
            >
              <UIcon :name="service.icon" class="size-5 sm:size-6" />
            </div>
            <div class="flex-1 min-w-0">
              <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white mb-2">
                {{ service.title }}
              </h3>
              <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed mb-3 sm:mb-4">
                {{ service.description }}
              </p>
              <ULink
                class="inline-flex items-center text-sm font-semibold text-primary hover:text-secondary transition-colors"
                to="#"
              >
                {{ $t('services.explore') }}
                <UIcon name="i-lucide-arrow-right" class="size-4 ml-1" />
              </ULink>
            </div>
          </div>
          <div class="absolute -bottom-4 -right-4 opacity-[0.05] group-hover:opacity-[0.1] transition-opacity">
            <UIcon :name="service.bgIcon" class="size-20 sm:size-24 text-primary" />
          </div>
        </UCard>
      </div>
    </UContainer>
  </section>
</template>

<script setup lang="ts">
const { t } = useI18n()
const services = computed(() => [
  {
    icon: 'i-lucide-briefcase',
    bgIcon: 'i-lucide-bar-chart-3',
    title: t('services.items.enterprise.title'),
    description: t('services.items.enterprise.description'),
  },
  {
    icon: 'i-lucide-fingerprint',
    bgIcon: 'i-lucide-brain',
    title: t('services.items.ai.title'),
    description: t('services.items.ai.description'),
  },
  {
    icon: 'i-lucide-shield',
    bgIcon: 'i-lucide-lock',
    title: t('services.items.security.title'),
    description: t('services.items.security.description'),
  },
])
</script>

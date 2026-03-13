<template>
  <section class="relative py-10">
    <div class="absolute inset-0"></div>
    <UContainer class="relative z-10">
      <div
        v-motion
        :initial="{ opacity: 0, y: 30 }"
        :visible-once="{ opacity: 1, y: 0, transition: { duration: 600 } }"
        class="text-center mb-10 sm:mb-16"
      >
        <span class="text-secondary font-semibold tracking-wide uppercase text-sm" v-if="sectionData?.subtitle" v-html="sectionData.subtitle"></span>
        <h2 class="mt-2 text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white" v-if="sectionData?.title" v-html="sectionData.title">
        </h2>
        <p class="mt-4 text-slate-600 dark:text-slate-400 max-w-2xl mx-auto" v-if="sectionData?.description" v-html="sectionData.description"></p>
        <div class="mt-6" v-if="sectionData?.button?.label && sectionData?.button?.url">
          <UButton :to="sectionData.button.url" color="primary" variant="solid">
            {{ sectionData.button.label }}
          </UButton>
        </div>
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
          <div class="flex flex-col h-full z-10 relative">
            <div class="flex items-start justify-between mb-4 sm:mb-6">
              <div
                class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-primary/10 text-primary flex items-center justify-center shadow-sm"
              >
                <div v-if="services[0].image" class="w-full h-full rounded-2xl overflow-hidden">
                  <NuxtImg :src="services[0].image" class="w-full h-full object-cover" />
                </div>
                <i v-else-if="services[0].icon" :class="services[0].icon + ' text-3xl'"></i>
              </div>
              <UBadge color="primary" variant="subtle" size="sm">Nổi bật</UBadge>
            </div>
            <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-slate-900 dark:text-white mb-2 sm:mb-3" v-html="services[0].name">
            </h3>
            <p class="text-slate-600 dark:text-slate-400 text-sm sm:text-base leading-relaxed mb-4 sm:mb-6 flex-1" v-html="services[0].description">
            </p>
            <ULink
              class="inline-flex items-center text-sm font-semibold text-primary hover:text-secondary transition-colors"
              :to="services[0].slug ? '/' + services[0].slug : '#'"
            >
              Khám phá ngay
              <UIcon name="i-lucide-arrow-right" class="size-4 ml-1" />
            </ULink>
          </div>
          <div class="absolute -bottom-8 -right-8 opacity-[0.05] group-hover:opacity-[0.1] transition-opacity z-0">
            <i v-if="services[0].icon" :class="services[0].icon + ' text-[10rem] text-primary'"></i>
          </div>
        </UCard>

        <!-- Remaining items -->
        <UCard
          v-for="(service, i) in services.slice(1)"
          :key="service.name"
          v-motion
          :initial="{ opacity: 0, y: 30 }"
          :visible-once="{ opacity: 1, y: 0, transition: { duration: 600, delay: 150 + (Number(i) * 120) } }"
          class="card-hover-glow group transition-all duration-300 relative overflow-hidden lg:col-span-2"
        >
          <div class="flex items-start gap-4 z-10 relative">
            <div
              class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0"
            >
                <div v-if="service.image" class="w-full h-full rounded-xl overflow-hidden">
                  <NuxtImg :src="service.image" class="w-full h-full object-cover" />
                </div>
                <i v-else-if="service.icon" :class="service.icon + ' text-xl'"></i>
            </div>
            <div class="flex-1 min-w-0">
              <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white mb-2" v-html="service.name">
              </h3>
              <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed mb-3 sm:mb-4 line-clamp-2" v-html="service.description">
              </p>
              <ULink
                class="inline-flex items-center text-sm font-semibold text-primary hover:text-secondary transition-colors"
                :to="service.slug ? '/' + service.slug : '#'"
              >
                Khám phá ngay
                <UIcon name="i-lucide-arrow-right" class="size-4 ml-1" />
              </ULink>
            </div>
          </div>
          <div class="absolute -bottom-4 -right-4 opacity-[0.05] group-hover:opacity-[0.1] transition-opacity z-0">
            <i v-if="service.icon" :class="service.icon + ' text-[6rem] text-primary'"></i>
          </div>
        </UCard>
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
</script>

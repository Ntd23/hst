<template>
  <section class="py-16 relative overflow-hidden" :style="{ backgroundColor: sectionData.background_color || 'transparent' }">
    <div class="absolute inset-0 bg-primary/5 -z-10" />
    <UContainer>
      <UCard class="shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-56 h-56 sm:w-96 sm:h-96 bg-blue-400/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2" />
        <div class="absolute bottom-0 left-0 w-44 h-44 sm:w-64 sm:h-64 bg-secondary/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2" />
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 sm:gap-12 items-center relative z-10">
          <div class="lg:col-span-1">
            <h2 v-if="sectionData.title" class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-900 dark:text-white mb-4" v-html="sectionData.title">
            </h2>
            <p v-if="sectionData.description" class="text-slate-600 dark:text-slate-400 mb-8" v-html="sectionData.description">
            </p>
            
            <UButton
              v-if="sectionData.button?.label"
              :to="sectionData.button.url || '#'"
              color="neutral"
              variant="solid"
              size="md"
              trailing-icon="i-lucide-arrow-right"
              class="inline-flex items-center justify-center rounded-xl font-semibold w-full sm:w-auto btn-dark"
            >
              {{ sectionData.button.label }}
            </UButton>
          </div>
          <div class="lg:col-span-1 h-full">
            <div class="relative w-full h-full rounded-2xl overflow-hidden shadow-xl" v-if="sectionData.image || sectionData.background_image">
              <NuxtImg
                :alt="sectionData.title || 'Contact'"
                class="w-full h-full object-cover"
                :src="sectionData.image || sectionData.background_image"
              />
              <div class="absolute inset-0 from-slate-900/20 to-transparent" />
            </div>
          </div>
        </div>
      </UCard>
    </UContainer>
  </section>
</template>

<script setup lang="ts">
const props = defineProps<{
  data?: any
}>()

const sectionData = computed(() => props.data?.data || props.data || {})
</script>

<template>
  <section class="py-16 sm:py-20 lg:py-24 relative overflow-hidden">
    <UContainer>
      <div class="glass-panel rounded-3xl overflow-hidden relative">
        <div class="grid grid-cols-1 lg:grid-cols-2">
          <div class="relative h-80 sm:h-96 lg:h-auto">
            <NuxtImg
              v-if="sectionData.image"
              :alt="sectionData.title || 'About Image'"
              class="absolute inset-0 w-full h-full object-cover"
              :src="sectionData.image"
            />
            <div
              class="absolute inset-0 from-primary/80 to-transparent mix-blend-multiply"
            />
            <div
              v-if="tabs.length > 0"
              class="absolute bottom-4 sm:bottom-8 left-4 sm:left-8 right-4 sm:right-8 glass-panel-darker p-4 sm:p-6 rounded-2xl"
            >
              <div v-for="(tab, idx) in tabs.slice(0, 2)" :key="idx" class="flex items-center gap-4 mb-4 last:mb-0">
                <div :class="['p-2.5 sm:p-3 rounded-full', idx === 0 ? 'bg-blue-100 text-primary' : 'bg-yellow-100 text-secondary']">
                  <UIcon :name="tab.icon || 'i-lucide-check-circle'" class="size-5 sm:size-6" />
                </div>
                <div>
                  <h4 class="font-bold text-sm sm:text-base text-slate-800" v-html="tab.title">
                  </h4>
                  <p class="text-sm text-slate-600" v-if="tab.description" v-html="tab.description">
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div
            class="p-6 sm:p-10 lg:p-16 flex flex-col justify-center relative"
          >
            <div
              class="absolute top-0 right-0 w-64 h-64 bg-secondary/10 rounded-full blur-3xl -z-10 translate-x-1/2 -translate-y-1/2"
            />
            <span
              v-if="sectionData.subtitle"
              class="text-secondary font-semibold tracking-wide uppercase text-sm mb-4 block"
              v-html="sectionData.subtitle"
              ></span>
            <h2
              v-if="sectionData.title"
              class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white mb-6"
              v-html="sectionData.title"
            >
            </h2>
            <p v-if="sectionData.description" class="text-slate-600 dark:text-slate-400 mb-6 leading-relaxed" v-html="sectionData.description">
            </p>
            <div class="flex flex-col sm:flex-row gap-4 mt-4">
              <UButton
                v-if="sectionData.button?.label"
                :to="sectionData.button?.url || '#'"
                color="primary"
                variant="solid"
                size="lg"
                trailing-icon="i-lucide-arrow-right"
                class="rounded-xl font-semibold w-full sm:w-auto btn-primary-elevated"
              >
                {{ sectionData.button.label }}
              </UButton>
              <div class="flex flex-wrap items-center gap-4 px-2 sm:px-4 py-2" v-if="sectionData.image_1 || sectionData.image_2">
                <div class="flex -space-x-4">
                  <NuxtImg
                    v-if="sectionData.image_1"
                    alt="Image 1"
                    class="w-10 h-10 rounded-full border-2 border-white object-cover"
                    :src="sectionData.image_1"
                  />
                  <NuxtImg
                    v-if="sectionData.image_2"
                    alt="Image 2"
                    class="w-10 h-10 rounded-full border-2 border-white object-cover"
                    :src="sectionData.image_2"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </UContainer>
  </section>
</template>

<script setup lang="ts">
const props = defineProps<{
  data?: any
}>()

const sectionData = computed(() => props.data?.data || props.data || {})
const tabs = computed(() => sectionData.value?.tabs || [])
</script>

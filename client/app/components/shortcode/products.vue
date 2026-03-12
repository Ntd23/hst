<template>
  <section class="py-16">
    <UContainer>
      <div
        v-motion
        :initial="{ opacity: 0, x: -40 }"
        :visible-once="{ opacity: 1, x: 0, transition: { duration: 600 } }"
        class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4 mb-10 sm:mb-12"
      >
        <div>
          <span class="text-secondary font-semibold tracking-wide uppercase text-sm" v-if="sectionData.subtitle" v-html="sectionData.subtitle"></span>
          <h2 class="mt-2 text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white" v-if="sectionData.title" v-html="sectionData.title">
          </h2>
        </div>
        <div class="flex gap-2 mt-1 sm:mt-0">
          <UButton color="neutral" variant="outline" icon="i-lucide-chevron-left" square class="btn-icon-circle btn-icon-circle-outline" />
          <UButton color="primary" variant="solid" icon="i-lucide-chevron-right" square class="btn-icon-circle btn-icon-circle-primary" />
        </div>
      </div>

      <!-- Overlay Showcase: phone=1col, tablet=2col, desktop=3col -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6 lg:gap-8">
        <a
          v-for="(product, i) in products"
          :key="product.id || product.name || i"
          :href="product.url_client || '#'"
          target="_blank"
          v-motion
          :initial="{ opacity: 0, y: 30 }"
          :visible-once="{ opacity: 1, y: 0, transition: { duration: 600, delay: Number(i) * 120 } }"
          class="group card-hover-glow rounded-2xl overflow-hidden relative cursor-pointer block"
        >
          <div class="bg-slate-100 dark:bg-slate-800 h-48 sm:h-52 md:h-56 lg:h-64 w-full flex items-center justify-center overflow-hidden relative">
            <template v-if="product.img_full || product.img_featured">
              <NuxtImg :src="product.img_full || product.img_featured" loading="lazy" class="w-full h-full object-cover relative z-10 transition-transform duration-700 group-hover:scale-105" />
            </template>
            <div v-else class="text-slate-400">Không có ảnh</div>
          </div>

          <!-- Overlay info -->
          <div class="absolute inset-x-0 bottom-0 p-4 sm:p-5 lg:p-6 bg-gradient-to-t from-slate-900/90 via-slate-900/60 to-transparent translate-y-2 sm:translate-y-4 opacity-90 sm:opacity-80 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300 z-20">
            <div class="flex items-end justify-between">
              <div>
                <h3 class="text-base sm:text-lg font-bold text-white mb-0.5 sm:mb-1 line-clamp-2" v-html="product.name">
                </h3>
              </div>
              <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white shrink-0 ml-2">
                <UIcon name="i-lucide-arrow-up-right" class="size-4 sm:size-5" />
              </div>
            </div>
          </div>
        </a>
      </div>
    </UContainer>
  </section>
</template>

<script setup lang="ts">
const props = defineProps<{
  data?: any
}>()

const sectionData = computed(() => props.data || {})
const products = computed(() => props.data?.items || [])
</script>

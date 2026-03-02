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
          <span class="text-secondary font-semibold tracking-wide uppercase text-sm">{{ $t('products.subtitle') }}</span>
          <h2 class="mt-2 text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">
            {{ $t('products.title') }}
          </h2>
        </div>
        <div class="flex gap-2 mt-1 sm:mt-0">
          <UButton color="neutral" variant="outline" icon="i-lucide-chevron-left" square class="btn-icon-circle btn-icon-circle-outline" />
          <UButton color="primary" variant="solid" icon="i-lucide-chevron-right" square class="btn-icon-circle btn-icon-circle-primary" />
        </div>
      </div>

      <!-- Overlay Showcase: phone=1col, tablet=2col, desktop=3col -->
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5 sm:gap-6 lg:gap-8">
        <div
          v-for="(product, i) in products"
          :key="product.title"
          v-motion
          :initial="{ opacity: 0, y: 30 }"
          :visible-once="{ opacity: 1, y: 0, transition: { duration: 600, delay: i * 120 } }"
          class="group card-hover-glow rounded-2xl overflow-hidden relative cursor-pointer"
        >
          <div class="bg-slate-100 dark:bg-slate-800 h-48 sm:h-52 md:h-56 lg:h-64 w-full flex items-center justify-center overflow-hidden relative">
            <component :is="product.visual" />
          </div>

          <!-- Overlay info -->
          <div class="absolute inset-x-0 bottom-0 p-4 sm:p-5 lg:p-6 bg-gradient-to-t from-slate-900/90 via-slate-900/60 to-transparent translate-y-2 sm:translate-y-4 opacity-90 sm:opacity-80 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
            <div class="flex items-end justify-between">
              <div>
                <h3 class="text-base sm:text-lg font-bold text-white mb-0.5 sm:mb-1">
                  {{ product.title }}
                </h3>
                <p class="text-xs sm:text-sm text-white/70">{{ product.subtitle }}</p>
              </div>
              <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white shrink-0 ml-2">
                <UIcon name="i-lucide-arrow-up-right" class="size-4 sm:size-5" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </UContainer>
  </section>
</template>

<script setup lang="ts">
import { h } from 'vue'

const SalesVisual = () => h('div', {
  class: 'absolute top-8 left-4 right-4 bg-white dark:bg-slate-900 rounded-t-lg shadow-lg h-full border border-slate-200 dark:border-slate-700 p-2'
}, [
  h('div', { class: 'flex gap-1 mb-2' }, [
    h('div', { class: 'w-2 h-2 rounded-full bg-red-400' }),
    h('div', { class: 'w-2 h-2 rounded-full bg-yellow-400' }),
    h('div', { class: 'w-2 h-2 rounded-full bg-green-400' }),
  ]),
  h('div', { class: 'space-y-2' }, [
    h('div', { class: 'h-2 w-3/4 bg-slate-100 dark:bg-slate-700 rounded' }),
    h('div', { class: 'h-2 w-1/2 bg-slate-100 dark:bg-slate-700 rounded' }),
    h('div', { class: 'grid grid-cols-2 gap-2 mt-4' }, [
      h('div', { class: 'h-16 bg-blue-50 dark:bg-slate-800 rounded' }),
      h('div', { class: 'h-16 bg-blue-50 dark:bg-slate-800 rounded' }),
    ]),
  ]),
])

const ChartVisual = () => h('div', {
  class: 'absolute inset-x-6 sm:inset-x-8 top-8 sm:top-10 bg-slate-900 rounded-t-lg shadow-2xl p-4'
}, [
  h('div', { class: 'text-center text-xs text-white/50 mb-4' }, 'SOFTWAKE'),
  h('div', { class: 'flex justify-center items-end gap-1 h-20' }, [
    h('div', { class: 'w-2 bg-primary h-10 rounded-t-sm' }),
    h('div', { class: 'w-2 bg-secondary h-16 rounded-t-sm' }),
    h('div', { class: 'w-2 bg-primary h-12 rounded-t-sm' }),
    h('div', { class: 'w-2 bg-secondary h-8 rounded-t-sm' }),
  ]),
])

const PlatformVisual = () => h('div', { class: 'absolute inset-0 flex items-center justify-center' },
  h('div', {
    class: 'w-3/4 h-3/4 bg-white dark:bg-slate-900 rounded-lg shadow-lg border border-slate-200 dark:border-slate-700 flex'
  }, [
    h('div', { class: 'w-1/3 border-r border-slate-100 dark:border-slate-800 p-2' },
      h('div', { class: 'space-y-2' }, [
        h('div', { class: 'h-2 w-full bg-slate-100 dark:bg-slate-800 rounded' }),
        h('div', { class: 'h-2 w-full bg-slate-100 dark:bg-slate-800 rounded' }),
      ]),
    ),
    h('div', { class: 'w-2/3 p-2' }, [
      h('div', { class: 'h-2 w-1/2 bg-slate-100 dark:bg-slate-800 rounded mb-2' }),
      h('div', { class: 'h-20 bg-blue-50 dark:bg-slate-800 rounded' }),
    ]),
  ]),
)

const { t } = useI18n()
const products = computed(() => [
  { title: t('products.items.sales.title'), subtitle: t('products.items.sales.subtitle'), visual: SalesVisual },
  { title: t('products.items.english.title'), subtitle: t('products.items.english.subtitle'), visual: ChartVisual },
  { title: t('products.items.platform.title'), subtitle: t('products.items.platform.subtitle'), visual: PlatformVisual },
])
</script>

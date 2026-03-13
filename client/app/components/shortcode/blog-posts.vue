<template>
  <section class="py-16">
    <UContainer>
      <div
        v-motion
        :initial="{ opacity: 0, x: -40 }"
        :visible-once="{ opacity: 1, x: 0, transition: { duration: 600 } }"
        class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4 mb-8 sm:mb-10"
      >
        <div>
          <span class="text-secondary font-semibold tracking-wide uppercase text-sm">{{ $t('news.subtitle') }}</span>
          <h2 class="mt-2 text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">
            {{ $t('news.title') }}
          </h2>
        </div>
        <ULink class="hidden md:flex items-center text-primary font-medium hover:text-secondary transition-colors" to="#">
          {{ $t('news.viewAll') }}
          <UIcon name="i-lucide-arrow-right" class="size-4 ml-1" />
        </ULink>
      </div>

      <!-- phone: 1col stacked, tablet: 2col, desktop: featured+sidebar -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 sm:gap-6 lg:gap-8">
        <!-- Featured article -->
        <div
          v-if="articles.length"
          class="sm:col-span-2 lg:col-span-3 card-hover-glow rounded-2xl overflow-hidden relative group cursor-pointer"
        >
          <div class="h-60 sm:h-72 lg:h-96 bg-slate-900 relative flex items-end">
            <component :is="articles[0]?.visual" />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent" />
            <div class="relative p-5 sm:p-6 lg:p-8 w-full">
              <UBadge color="primary" variant="subtle" size="sm" class="mb-2 sm:mb-3">
                {{ articles[0]?.category }}
              </UBadge>
              <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-white mb-1 sm:mb-2">
                {{ articles[0]?.title }}
              </h3>
              <p class="text-sm text-white/70 mb-2 sm:mb-3 line-clamp-2 hidden sm:block">{{ articles[0]?.excerpt }}</p>
              <div class="flex items-center justify-between">
                <span class="text-xs text-white/50">{{ articles[0]?.date }}</span>
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-white/10 backdrop-blur-sm flex items-center justify-center text-white">
                  <UIcon name="i-lucide-arrow-up-right" class="size-4 sm:size-5" />
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Side list articles -->
        <div class="sm:col-span-2 lg:col-span-2 flex flex-col gap-4 sm:gap-5">
          <div
            v-for="article in articles.slice(1)"
            :key="article.title"
            class="card-hover-glow rounded-2xl overflow-hidden flex flex-row group cursor-pointer bg-white dark:bg-slate-800 flex-1"
          >
            <!-- Compact visual -->
            <div class="w-24 sm:w-28 lg:w-32 shrink-0 bg-slate-900 relative overflow-hidden">
              <component :is="article.visual" />
            </div>
            <!-- Text -->
            <div class="p-3 sm:p-4 flex flex-col justify-center flex-1 min-w-0">
              <span class="text-xs font-bold text-primary uppercase mb-1">{{ article.category }}</span>
              <h3 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white mb-1 line-clamp-2 group-hover:text-primary transition-colors">
                {{ article.title }}
              </h3>
              <span class="text-xs text-slate-400">{{ article.date }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-6 sm:mt-8 md:hidden">
        <ULink class="inline-flex items-center text-primary font-medium hover:text-secondary transition-colors" to="#">
          Xem tất cả tin tức
          <UIcon name="i-lucide-arrow-right" class="size-4 ml-1" />
        </ULink>
      </div>
    </UContainer>
  </section>
</template>

<script setup lang="ts">
import { h } from 'vue'

const BarChartVisual = () => h('div', { class: 'absolute inset-0' }, [
  h('div', { class: 'absolute inset-0 from-slate-900 to-transparent z-10' }),
  h('div', { class: 'absolute inset-x-6 bottom-6 h-24 flex items-end gap-2 z-0 opacity-60' }, [
    h('div', { class: 'w-1/6 bg-blue-500 h-1/2 rounded-t-sm' }),
    h('div', { class: 'w-1/6 bg-blue-400 h-3/4 rounded-t-sm' }),
    h('div', { class: 'w-1/6 bg-primary h-full rounded-t-sm' }),
    h('div', { class: 'w-1/6 bg-blue-600 h-2/3 rounded-t-sm' }),
    h('div', { class: 'w-1/6 bg-blue-300 h-1/3 rounded-t-sm' }),
    h('div', { class: 'w-1/6 bg-secondary h-4/5 rounded-t-sm' }),
  ]),
])

const WaveVisual = () => h('div', { class: 'absolute inset-0' }, [
  h('svg', {
    class: 'absolute bottom-0 left-0 w-full h-full opacity-40 text-primary',
    preserveAspectRatio: 'none',
    viewBox: '0 0 1440 320',
  }, h('path', {
    d: 'M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,224C672,245,768,267,864,261.3C960,256,1056,224,1152,197.3C1248,171,1344,149,1392,138.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z',
    fill: 'currentColor',
    'fill-opacity': '1',
  })),
  h('div', { class: 'absolute inset-0 from-transparent to-slate-900/80' }),
])

const SpinnerVisual = () => h('div', { class: 'absolute inset-0 flex items-center justify-center' },
  h('div', { class: 'w-16 h-16 rounded-full border-4 border-t-primary border-r-secondary border-b-blue-400 border-l-white animate-spin' }),
)

const { t } = useI18n()
const articles = computed(() => [
  {
    category: t('news.items.article1.category'),
    title: t('news.items.article1.title'),
    excerpt: t('news.items.article1.excerpt'),
    date: '12/05/2024',
    visual: BarChartVisual,
  },
  {
    category: t('news.items.article2.category'),
    title: t('news.items.article2.title'),
    excerpt: t('news.items.article2.excerpt'),
    date: '10/05/2024',
    visual: WaveVisual,
  },
  {
    category: t('news.items.article3.category'),
    title: t('news.items.article3.title'),
    excerpt: t('news.items.article3.excerpt'),
    date: '08/05/2024',
    visual: SpinnerVisual,
  },
])
</script>

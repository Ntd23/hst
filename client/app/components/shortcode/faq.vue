<template>
  <section class="py-16 bg-white/30 dark:bg-slate-900/30">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 sm:gap-12 lg:gap-16">
        <div>
          <h2 v-if="sectionData.title" class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white mb-6 sm:mb-8" v-html="sectionData.title">
          </h2>
          <p v-if="sectionData.description" class="text-sm sm:text-base text-slate-600 dark:text-slate-400 mb-6 sm:mb-8" v-html="sectionData.description">
          </p>
          <div class="space-y-3">
            <details
              v-for="item in faqs"
              :key="item.id || item.question"
              class="group faq-item rounded-xl overflow-hidden border border-slate-200/60 dark:border-slate-700/50 bg-white/70 dark:bg-slate-800/50 backdrop-blur-sm transition-all duration-300 open:border-primary/40 open:shadow-md open:shadow-primary/5"
            >
              <summary
                class="flex justify-between items-center gap-4 p-4 sm:p-5 cursor-pointer list-none select-none"
              >
                <span class="font-semibold text-slate-800 dark:text-white transition-colors group-open:text-primary" v-html="item.question">
                </span>
                <div class="shrink-0 w-8 h-8 rounded-lg flex items-center justify-center bg-slate-100 dark:bg-slate-700 group-open:bg-primary/10 dark:group-open:bg-primary/20 transition-colors duration-300">
                  <UIcon
                    name="i-lucide-chevron-down"
                    class="size-4 text-slate-400 group-open:text-primary transition-transform duration-300 group-open:rotate-180"
                  />
                </div>
              </summary>
              <div class="px-5 pb-5 text-sm text-slate-600 dark:text-slate-300 leading-relaxed border-t border-slate-100 dark:border-slate-700/50 pt-4 mx-4" v-html="item.answer">
              </div>
            </details>
          </div>
        </div>
        <div class="relative flex items-center">
          <div class="relative w-full glass-panel p-2 rounded-3xl overflow-hidden">
            <NuxtImg
              v-if="sectionData.image"
              :alt="sectionData.title || 'FAQ'"
              class="w-full h-full object-cover rounded-2xl"
              :src="sectionData.image"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent rounded-2xl" />
            <div class="absolute bottom-4 sm:bottom-8 left-4 sm:left-8 right-4 sm:right-8 text-white" v-if="sectionData.floating_block">
              <div class="w-10 h-10 sm:w-12 sm:h-12 bg-primary rounded-full flex items-center justify-center mb-3 sm:mb-4">
                <i :class="(sectionData.floating_block.icon || 'ti ti-24-hours') + ' text-xl text-white'" />
              </div>
              <h3 class="text-xl sm:text-2xl font-bold mb-2" v-html="sectionData.floating_block.title"></h3>
              <p class="text-sm text-slate-200 mb-0" v-html="sectionData.floating_block.description">
              </p>
            </div>
          </div>
          <div
            class="hidden sm:block absolute -top-6 -right-6 w-24 h-24 bg-secondary/10 backdrop-blur-md rounded-full border border-secondary/20 z-10 animate-float"
          />
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
const props = defineProps<{
  data?: any
}>()

const sectionData = computed(() => props.data || {})
const faqs = computed(() => props.data?.items || [])
</script>

<style scoped>
details > summary {
  list-style: none;
}

details > summary::-webkit-details-marker {
  display: none;
}

@keyframes float {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-10px); }
}

.animate-float {
  animation: float 4s ease-in-out infinite;
}
</style>

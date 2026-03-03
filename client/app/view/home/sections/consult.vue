<template>
  <section class="py-16 relative overflow-hidden">
    <div class="absolute inset-0 bg-primary/5 -z-10" />
    <UContainer>
      <UCard class="shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-56 h-56 sm:w-96 sm:h-96 bg-blue-400/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2" />
        <div class="absolute bottom-0 left-0 w-44 h-44 sm:w-64 sm:h-64 bg-secondary/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2" />
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 sm:gap-12 items-center relative z-10">
          <div class="lg:col-span-1">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-900 dark:text-white mb-4">
              {{ $t('consult.title') }}
            </h2>
            <p class="text-slate-600 dark:text-slate-400 mb-8">
              {{ $t('consult.description') }}
            </p>
            <ul class="space-y-4 mb-8">
              <li v-for="item in benefits" :key="item" class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-primary">
                  <UIcon name="i-lucide-check" class="size-4" />
                </div>
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ item }}</span>
              </li>
            </ul>
            <UButton
              color="neutral"
              variant="solid"
              size="md"
              trailing-icon="i-lucide-arrow-right"
              class="inline-flex items-center justify-center rounded-xl font-semibold w-full sm:w-auto btn-dark"
            >
              {{ $t('consult.viewMore') }}
            </UButton>
          </div>
          <div class="lg:col-span-1 h-full">
            <div class="relative w-full h-full rounded-2xl overflow-hidden shadow-xl">
              <img
                alt="Professionals working together"
                class="w-full h-full object-cover"
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuBxq94X-wofrUQQMMHkxSw2z54dBeAF3r46zvJ8f2VScqlIYilys0SlGqcDUd-zHaps-gOBnQDZ4MxFVVtVc81oE0AtsHgyIcdohootSRgm74apWGXT5snjJ_m5ACDhLBb7f8NyfeOIbRjzt76C_nTJK_8QaAVNZ_6PchJDWEZrm9UEoMXJAPoc-j6HYABKHmbXM4FRsW4FFZYz7xOhN5-qSful5g2WBm7fP5IQ7akEi13Yhx7rccr2nawkNP18jBbn3Ak9ay1JpSg"
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
const { t } = useI18n()
const benefits = computed(() => [
  t('consult.benefit1'),
  t('consult.benefit2'),
  t('consult.benefit3'),
])
</script>

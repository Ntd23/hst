<template>
  <section class="relative overflow-hidden">
    <!-- Background decorations -->
    <div class="absolute inset-0 -z-10">
      <div class="absolute top-0 left-1/4 w-96 h-96 rounded-full blur-3xl" />
      <div class="absolute bottom-0 right-1/4 w-64 h-64 rounded-full blur-3xl" />
    </div>

    <UContainer>
      <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight mb-4">
        {{ title || items[items.length - 1]?.label }}
      </h1>

      <nav aria-label="Breadcrumb">
        <ol class="flex flex-wrap items-center gap-1.5 text-sm sm:text-base text-slate-500">
          <!-- Trang chủ (luôn có) -->
          <li class="flex items-center gap-1.5">
            <NuxtLink
              to="/"
              class="flex items-center gap-1 hover:text-primary transition-colors duration-200 font-medium"
            >
              <UIcon name="i-lucide-home" class="size-4" />
              <span>{{ homeLabel }}</span>
            </NuxtLink>
          </li>

          <!-- Dynamic items -->
          <li
            v-for="(item, index) in items"
            :key="index"
            class="flex items-center gap-1.5"
          >
            <UIcon name="i-lucide-chevron-right" class="size-4 text-slate-300" />
            <NuxtLink
              v-if="item.to"
              :to="item.to"
              class="hover:text-primary transition-colors duration-200 font-medium"
            >
              {{ item.label }}
            </NuxtLink>
            <span
              v-else
              class="text-primary font-semibold truncate max-w-[280px] sm:max-w-none"
            >
              {{ item.label }}
            </span>
          </li>
        </ol>
      </nav>
    </UContainer>
  </section>
</template>

<script setup lang="ts">
interface BreadcrumbItem {
  label: string
  to?: string
}

withDefaults(defineProps<{
  items: BreadcrumbItem[]
  title?: string
  homeLabel?: string
}>(), {
  homeLabel: 'Trang chủ',
})
</script>

<style scoped>

</style>

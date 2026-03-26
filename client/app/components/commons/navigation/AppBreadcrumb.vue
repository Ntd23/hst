<template>
  <section class="relative mb-10 overflow-hidden">
    <div class="absolute inset-0 -z-10">
      <div class="absolute left-1/4 top-0 h-96 w-96 rounded-full blur-3xl" />
      <div class="absolute bottom-0 right-1/4 h-64 w-64 rounded-full blur-3xl" />
    </div>

    <UContainer>
      <h1 class="mb-4 text-3xl font-bold tracking-tight sm:text-4xl md:text-5xl">
        {{ title || items[items.length - 1]?.label }}
      </h1>

      <nav aria-label="Breadcrumb">
        <ol class="flex flex-wrap items-center gap-1.5 text-sm text-slate-500 sm:text-base">
          <li class="flex items-center gap-1.5">
            <NuxtLink
              to="/"
              class="flex items-center gap-1 font-medium transition-colors duration-200 hover:text-primary"
            >
              <UIcon name="i-lucide-home" class="size-4" />
              <span>{{ resolvedHomeLabel }}</span>
            </NuxtLink>
          </li>

          <li
            v-for="(item, index) in items"
            :key="index"
            class="flex items-center gap-1.5"
          >
            <UIcon name="i-lucide-chevron-right" class="size-4 text-slate-300" />
            <NuxtLink
              v-if="item.to"
              :to="item.to"
              class="font-medium transition-colors duration-200 hover:text-primary"
            >
              {{ item.label }}
            </NuxtLink>
            <span
              v-else
              class="max-w-[280px] truncate font-semibold text-primary sm:max-w-none"
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
  label: string;
  to?: string;
}

const props = withDefaults(
  defineProps<{
    items: BreadcrumbItem[];
    title?: string;
    homeLabel?: string;
  }>(),
  {
    homeLabel: "",
  }
);

const { translate } = useI18nText();
const resolvedHomeLabel = computed(
  () => props.homeLabel || translate("nav.home", "Trang chủ")
);
</script>

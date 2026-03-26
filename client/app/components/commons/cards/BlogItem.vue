<template>
  <div>
    <NuxtLink :to="`/blog/${props.slug}`">
      <NuxtImg :src="props.image" class="h-52 w-full object-cover" />
    </NuxtLink>
    <h3 class="mt-4 line-clamp-2 text-lg font-bold leading-snug">
      {{ props.title }}
    </h3>

    <div class="mt-3 flex items-center justify-between gap-3 text-xs font-semibold uppercase">
      <NuxtLink
        :to="`/blog/${props.slug}`"
        class="flex rounded bg-blue-600 px-6 py-2 text-sm font-semibold uppercase text-white transition hover:bg-blue-700"
      >
        <span>{{ blogReadMoreLabel }}</span>
        <UIcon name="solar:arrow-right-outline" class="size-5" />
      </NuxtLink>
      <div v-if="formattedDate" class="flex items-center gap-2 text-sm text-gray-500">
        <UIcon name="solar:calendar-broken" class="size-5" />
        <span>{{ formattedDate }}</span>
      </div>
    </div>

    <div class="mt-3 h-px w-full bg-gray-300"></div>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{
  title: string;
  image: string;
  slug: string;
  date?: string;
}>();

const { blogReadMoreLabel, formatDate } = useCommonCardText();
const formattedDate = computed(() => formatDate(props.date));
</script>

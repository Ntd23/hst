<template>
  <div>
    <NuxtLink :to="`/blog/${slug}`">
      <NuxtImg :src="image" class="w-full h-52 object-cover" />
    </NuxtLink>
    <h3 class="mt-4 font-bold text-lg leading-snug line-clamp-2">
      {{ title }}
    </h3>

    <div
      class="flex items-center gap-3 mt-3 text-xs font-semibold uppercase justify-between"
    >
      <NuxtLink
        :to="`/blog/${slug}`"
        class="flex px-6 py-2 bg-blue-600 text-white font-semibold uppercase text-sm rounded hover:bg-blue-700 transition"
      >
        <span>XEM THÊM</span>
        <UIcon name="solar:arrow-right-outline" class="size-5" />
      </NuxtLink>
      <div class="flex items-center gap-2 text-gray-500 text-sm">
        <UIcon name="solar:calendar-broken" class="size-5" />
        <span>08/03/2026</span>
      </div>
    </div>

    <div class="w-full h-px bg-gray-300 mt-3"></div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  title: string;
  image: string;
  slug: string;
}>();
</script>
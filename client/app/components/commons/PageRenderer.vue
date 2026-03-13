<template>
  <main class="relative overflow-hidden w-full">
    <div v-if="pending" class="flex min-h-[60vh] items-center justify-center">
      <UIcon name="i-lucide-loader-2" class="size-8 animate-spin text-primary" />
    </div>
    <template v-else-if="Shortcodes.length > 0">
      <component
        v-for="(Shortcode, index) in Shortcodes"
        :key="index"
        :is="Shortcode.component"
        :data="Shortcode.data"
        v-bind="index >= 3 ? { 'hydrate-on-visible': true } : {}"
      />
    </template>
    <div v-else class="py-24 text-center">
      <h1 class="text-2xl font-bold text-gray-800">Page Content Not Found</h1>
      <p class="text-gray-500 mt-2">The requested slug "{{ slug }}" returned no sections from the API.</p>
    </div>
  </main>
</template>

<script setup lang="ts">
import { useMappedShortcodes } from "~/composables/useMappedShortcodes";

const props = defineProps<{
  slug: string;
}>();

const { Shortcodes, mapSectionsToShortcodes } = useMappedShortcodes();
const { data: pageData, pending } = await usePageSections<any>(props.slug);

if (pageData.value?.sections) {
  mapSectionsToShortcodes(pageData.value.sections);
}
</script>

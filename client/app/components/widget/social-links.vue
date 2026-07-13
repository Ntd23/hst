<template>
  <div class="flex flex-wrap items-center justify-center gap-3 md:justify-end sm:m-auto">
    <ULink
      v-for="social in content.socials || []"
      :key="social.network"
      :to="social.url"
      target="_blank"
      class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-slate-200 transition hover:bg-primary hover:text-white"
    >
      <img
        v-if="social.icon_image"
        :src="social.icon_image"
        :alt="social.label || social.network || 'Social icon'"
        class="h-4 w-4 object-contain"
      />
      <CommonsBotbleIcon v-else :icon="social.icon" class="size-4" />
    </ULink>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{
  data?: any;
}>();

const content = computed(() => props.data || {});
</script>

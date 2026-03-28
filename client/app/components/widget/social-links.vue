<template>
  <div class="flex flex-wrap items-center justify-center gap-3 md:justify-end">
    <ULink
      v-for="social in content.socials || []"
      :key="social.network"
      :to="social.url"
      target="_blank"
      class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-slate-200 transition hover:bg-primary hover:text-white"
    >
      <span class="text-[10px] font-black uppercase">{{ social.network?.slice(0, 2) }}</span>
    </ULink>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{
  data?: any;
}>();

const content = computed(() => props.data || {});
</script>

<template>
  <div
    class="relative overflow-hidden rounded-2xl p-6 text-white"
    :style="{ background: content.background_color || '#191D88' }"
  >
    <div class="absolute right-0 top-0 translate-x-1/4 -translate-y-1/4 transform p-8 opacity-10">
      <UIcon name="i-lucide-headset" class="size-24" />
    </div>

    <h4 v-if="content.title" class="relative z-10 mb-3 text-xl font-black tracking-tight">
      {{ content.title }}
    </h4>

    <a
      v-if="content.phone_number"
      :href="`tel:${normalizedPhone}`"
      class="relative z-10 inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-3 text-sm font-bold text-white shadow-lg transition-colors hover:bg-blue-600"
    >
      <UIcon name="i-lucide-phone" class="size-5" />
      {{ content.phone_number }}
    </a>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{ data?: any }>();
const content = computed(() => props.data || {});
const normalizedPhone = computed(() => String(content.value.phone_number || "").replace(/\s+/g, ""));
</script>

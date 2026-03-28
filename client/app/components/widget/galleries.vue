<template>
  <div class="space-y-5">
    <h4
      v-if="content.title"
      class="text-base font-black tracking-tight text-white"
      v-html="content.title"
    />

    <div class="grid grid-cols-3 gap-3">
      <ULink
        v-for="item in content.items || []"
        :key="item.id"
        :to="item.url || '#'"
        class="group block overflow-hidden rounded-2xl"
      >
        <NuxtImg
          v-if="item.image"
          :src="item.image"
          :alt="item.name || 'Gallery image'"
          class="aspect-square w-full object-cover transition duration-500 group-hover:scale-110"
        />
        <div
          v-else
          class="aspect-square w-full rounded-2xl bg-white/10"
        />
      </ULink>
    </div>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{
  data?: any;
}>();

const content = computed(() => props.data || {});
</script>

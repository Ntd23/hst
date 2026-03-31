<template>
  <div class="space-y-5">
    <h4 v-if="content.title" class="text-base font-black tracking-tight text-slate-900">
      {{ content.title }}
    </h4>

    <div class="flex flex-wrap gap-2">
      <NuxtLink
        v-for="item in content.items || []"
        :key="item.id"
        :to="{ path: '/blog', query: item.slug ? { tag: item.slug } : {} }"
        class="rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-600 transition-colors hover:bg-primary hover:text-white"
      >
        #{{ decodeHtml(item.name) }}
      </NuxtLink>
    </div>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{ data?: any }>();
const content = computed(() => props.data || {});
<<<<<<< HEAD
const { decodeHtml } = useDecodeHtml();
=======
>>>>>>> origin/main
</script>

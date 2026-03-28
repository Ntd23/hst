<template>
  <div class="space-y-5">
    <h4 v-if="content.title" class="text-base font-black tracking-tight text-slate-900">
      {{ content.title }}
    </h4>

    <ul class="space-y-3">
      <li v-for="item in content.items || []" :key="item.id">
        <NuxtLink
          :to="{ path: '/blog', query: item.slug ? { category: item.slug } : {} }"
          class="group flex items-center justify-between text-slate-600 transition-colors hover:text-primary"
        >
          <span class="line-clamp-2">{{ item.name }}</span>
          <span class="text-xs font-semibold text-slate-400">({{ item.posts_count }})</span>
        </NuxtLink>
      </li>
    </ul>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{ data?: any }>();
const content = computed(() => props.data || {});
</script>

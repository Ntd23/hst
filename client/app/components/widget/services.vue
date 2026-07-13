<template>
  <div class="space-y-5">
    <h4 v-if="content.title" class="text-base font-black tracking-tight text-slate-900">
      {{ content.title }}
    </h4>

    <nav class="flex flex-col space-y-1">
      <NuxtLink
        v-for="item in content.items || []"
        :key="item.id"
        :to="item.url || (item.slug ? `/services/${item.slug}` : '#')"
        class="flex items-start justify-between gap-3 rounded-xl px-3 py-2 text-sm font-medium text-slate-600 transition-colors hover:bg-slate-50 hover:text-primary"
      >
        <span class="line-clamp-2">{{ item.name }}</span>
        <CommonsBotbleIcon icon="i-heroicons-arrow-right-20-solid" class="size-4 shrink-0" />
      </NuxtLink>
    </nav>
  </div>
</template>

<script setup lang="ts">
import CommonsBotbleIcon from "~/components/commons/BotbleIcon.vue";
const props = defineProps<{ data?: any }>();
const content = computed(() => props.data || {});
</script>

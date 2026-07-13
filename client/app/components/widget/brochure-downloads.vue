<template>
  <div class="space-y-5">
    <div>
      <h4 v-if="content.title" class="text-base font-black tracking-tight text-slate-900">
        {{ content.title }}
      </h4>
      <p v-if="content.description" class="mt-2 text-sm leading-relaxed text-slate-500">
        {{ content.description }}
      </p>
    </div>

    <div class="space-y-2">
      <a
        v-for="item in content.items || []"
        :key="item.url"
        :href="item.url"
        class="flex items-center gap-3 rounded-xl bg-blue-50/60 p-3 text-sm font-medium text-slate-700 transition-colors hover:bg-blue-50"
        target="_blank"
        rel="noopener noreferrer"
      >
        <img
          v-if="item.icon_image"
          :src="item.icon_image"
          :alt="item.name || 'Icon'"
          class="h-5 w-5 object-contain"
        />
        <CommonsBotbleIcon v-else :icon="item.icon" class="size-5 text-primary" />
        <span class="flex-1">{{ item.name }}</span>
        <span class="text-[10px] font-black uppercase text-slate-400">{{ item.extension }}</span>
      </a>
    </div>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{ data?: any }>();
const content = computed(() => props.data || {});
</script>

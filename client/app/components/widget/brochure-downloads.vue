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
        <UIcon :name="toUiIcon(item.icon)" class="size-5 text-primary" />
        <span class="flex-1">{{ item.name }}</span>
        <span class="text-[10px] font-black uppercase text-slate-400">{{ item.extension }}</span>
      </a>
    </div>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{ data?: any }>();
const content = computed(() => props.data || {});

const toUiIcon = (icon?: string) => {
  if (!icon) return "i-lucide-file";
  return `i-lucide-${icon.replace(/^ti ti-/, "")}`;
};
</script>

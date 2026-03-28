<template>
  <div class="space-y-5">
    <h4
      v-if="content.title"
      class="text-base font-black tracking-tight text-white"
      v-html="content.title"
    />

    <ul class="space-y-3 text-sm text-slate-300">
      <li v-for="(item, index) in content.items || []" :key="`${item.label}-${index}`">
        <ULink
          :to="item.url || '#'"
          :target="item.open_new_tab ? '_blank' : undefined"
          class="flex items-start gap-3 transition-colors hover:text-primary"
        >
          <UIcon
            v-if="item.icon"
            :name="toUiIcon(item.icon)"
            class="mt-0.5 size-4 shrink-0 text-primary"
          />
          <span class="leading-relaxed" v-html="item.label" />
        </ULink>
      </li>
    </ul>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{
  data?: any;
}>();

const content = computed(() => props.data || {});

const toUiIcon = (icon?: string) => {
  if (!icon) return "i-lucide-circle";

  return `i-lucide-${icon.replace(/^ti ti-/, "")}`;
};
</script>

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
          <img
            v-if="item.icon_image"
            :src="item.icon_image"
            :alt="item.label || 'Icon'"
            class="mt-0.5 h-4 w-4 shrink-0 object-contain"
          />
          <CommonsBotbleIcon
            v-else-if="item.icon"
            :icon="item.icon"
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
</script>

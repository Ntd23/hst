<template>
  <div v-if="mappedWidgets.length" class="space-y-6">
    <component
      :is="widget.component"
      v-for="(widget, index) in mappedWidgets"
      :key="widgetKey(widget, index)"
      :data="widget.data"
      class="glass-panel rounded-2xl p-6 shadow-md"
      :class="widgetClass(widget.meta?.widget)"
    />
  </div>
</template>

<script setup lang="ts">
import { useMappedWidgets } from "~/composables/layout/useMappedWidgets";

const props = defineProps<{
  widgets?: any[];
}>();

const { mapWidgets } = useMappedWidgets();

const mappedWidgets = computed(() => mapWidgets(props.widgets || []));

const widgetKey = (widget: any, index: number) =>
  `${widget.meta?.widget || "widget"}-${widget.meta?.position ?? index}`;

const widgetClass = (widgetType?: string) => {
  if (widgetType === "newsletter") {
    return "overflow-hidden border border-primary/15 bg-slate-950 text-white";
  }

  if (widgetType === "contact-block" || widgetType === "contact-form") {
    return "border-0 bg-transparent p-0 shadow-none";
  }

  return "border border-white/60 bg-white/85";
};
</script>

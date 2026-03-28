<template>
  <div class="text-center text-xs text-slate-400 md:text-left" v-html="content.content" />
</template>

<script setup lang="ts">
const props = defineProps<{
  data?: any;
}>();

const content = computed(() => props.data || {});
</script>

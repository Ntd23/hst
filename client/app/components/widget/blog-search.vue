<template>
  <div class="space-y-4">
    <h4 v-if="content.title" class="text-base font-black tracking-tight text-slate-900">
      {{ content.title }}
    </h4>

    <form class="rounded-2xl border border-slate-200 bg-white p-1" @submit.prevent="handleSubmit">
      <UInput
        v-model="query"
        icon="i-heroicons-magnifying-glass-20-solid"
        color="white"
        variant="none"
        :placeholder="content.placeholder || 'Search...'"
        class="h-11 w-full"
      />
    </form>
  </div>
</template>

<script setup lang="ts">
import { useDebounceFn } from "@vueuse/core";

const props = defineProps<{ data?: any }>();

const content = computed(() => props.data || {});
const query = ref("");
const route = useRoute();
const syncingFromRoute = ref(false);

const handleSubmit = async () => {
  const normalizedQuery = query.value.trim();

  await navigateTo({
    path: "/blog",
    query: normalizedQuery ? { q: normalizedQuery } : {},
  });
};

const handleDebouncedSubmit = useDebounceFn(async () => {
  await handleSubmit();
}, 2000);

watch(
  () => route.query.q,
  (value) => {
    syncingFromRoute.value = true;
    query.value = typeof value === "string" ? value : "";
    nextTick(() => {
      syncingFromRoute.value = false;
    });
  },
  { immediate: true }
);

watch(query, (value) => {
  if (syncingFromRoute.value) {
    return;
  }

  const normalizedQuery = value.trim();
  const currentQuery =
    typeof route.query.q === "string" ? route.query.q.trim() : "";

  if (normalizedQuery === currentQuery) {
    return;
  }

  handleDebouncedSubmit();
});
</script>

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
const props = defineProps<{ data?: any }>();

const content = computed(() => props.data || {});
const query = ref("");

const handleSubmit = async () => {
  await navigateTo({
    path: "/blog",
    query: query.value ? { q: query.value } : {},
  });
};
</script>

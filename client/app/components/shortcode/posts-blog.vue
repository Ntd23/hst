<template>
  <div class="container py-10">
    <!-- Title -->
    <h3
      v-if="ready"
      v-motion
      :initial="{ opacity: 0, y: -30 }"
      :visible-once="{ opacity: 1, y: 0, transition: { duration: 600 } }"
      class="text-4xl font-semibold leading-snug text-gray-900 transition-colors py-10"
    >
      {{ props.data.title }}
    </h3>

    <!-- Blog grid -->
    <div v-if="ready" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <div
        v-for="(item, i) in posts"
        :key="item.id"
        v-motion
        :initial="{ opacity: 0, y: 40 }"
        :visible-once="{
          opacity: 1,
          y: 0,
          transition: { duration: 600, delay: i * 120 },
        }"
      >
        <CommonsBlogItem
          :title="item.name"
          :image="item.image"
          :slug="item.slug"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";

const props = defineProps({
  data: {
    type: Object,
    default: () => ({}),
  },
});

const posts = props.data?.items || [];

const ready = ref(false);

onMounted(() => {
  requestAnimationFrame(() => {
    ready.value = true;
  });
});
</script>
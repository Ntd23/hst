<template>
  <div class="container py-10">
    <h3
      v-if="ready"
      v-motion
      :initial="{ opacity: 0, y: -30 }"
      :visible-once="{ opacity: 1, y: 0, transition: { duration: 600 } }"
      class="py-10 text-4xl font-semibold leading-snug text-gray-900 transition-colors"
    >
      {{ sectionData.title }}
    </h3>

    <div v-if="ready" class="grid grid-cols-1 gap-8 lg:grid-cols-3">
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
          :date="item.created_at"
        />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import CommonsBlogItem from "~/components/commons/cards/BlogItem.vue";

const props = defineProps({
  data: {
    type: Object,
    default: () => ({}),
  },
});

const { sectionData, posts, ready } = usePostsBlogShortcode(
  toRef(props, "data")
);
</script>

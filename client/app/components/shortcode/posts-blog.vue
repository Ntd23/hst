<template>
  <div class="container py-10">
    <h3
      class="py-10 text-4xl font-semibold leading-snug text-gray-900 transition-colors"
    >
      {{ sectionData.title }}
    </h3>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
      <div
        v-for="item in posts"
        :key="item.id"
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

const { sectionData, posts } = usePostsBlogShortcode(
  toRef(props, "data")
);
</script>

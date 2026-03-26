<template>
  <div class="container mx-auto">
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
      <CommonsProductItem
        v-for="web in webs"
        :key="web.index"
        :title="web.name"
        :image="web.img_full"
        :slug="route.fullPath + '/' + web.slug"
        :date="web.date"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import CommonsProductItem from "~/components/commons/cards/ProductItem.vue";

const props = defineProps({
  data: {
    type: Object,
    default: () => ({}),
  },
});

const { route, webs } = useWebDemosShortcode(toRef(props, "data"));
</script>


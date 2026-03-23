<template>
  <div class="container mx-auto">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <CommonsProductItem
        v-for="web in webs"
        :key="web.index"
        :title="web.name"
        :image="web.img_full"
        :slug="route.fullPath + '/' + web.slug"
        :date="web.date"
        :locale="props.data.locale"
      />
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  data: {
    type: Object,
    default: () => ({}),
  },
});
const route = useRoute();

console.log(props.data?.items);

const webs = props.data?.items || [];
</script>
<template>
  <div>
    <h3 class="uppercase font-bold text-4xl py-5">{{ props.title }}</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
      <CommonsBlogItem
        v-for="items in props.data"
        :key="items.id"
        :title="items.name"
        :image="items.image"
        :slug="items.slug"
      />
    </div>
  </div>
</template>
<script setup>
const props = defineProps({
  data: {
    type: Array,
    default: () => [],
  },
  title: {
    type: String,
    default: "",
  },
});
console.log(props.data);
</script>
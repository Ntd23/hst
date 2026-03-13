<template>
  <div class="grid grid-cols-12 gap-10">
    <div class="col-span-12 lg:col-span-8 relative">
      <NuxtImg
        :src="props.data.post_1.image"
        class="w-full h-[450px] object-cover"
      />

      <div
        class="absolute top-[400px] right-0 bg-[#e9e4d6] p-8 w-[85%] shadow-lg"
      >
        <h2 class="text-3xl font-extrabold leading-tight">
          {{ props.data.post_1.title }}
        </h2>

        <p class="text-gray-700 mt-4 line-clamp-2">
          {{ preview }}
        </p>

        <div
          class="flex items-center gap-3 mt-3 text-xs font-semibold uppercase justify-between"
        >
          <NuxtLink
            :to="`/blog/${props.data.post_1.slug}`"
            class="flex px-6 py-2 bg-blue-600 text-white font-semibold uppercase text-sm rounded hover:bg-blue-700 transition"
          >
            <span>XEM THÊM</span>
            <UIcon name="solar:arrow-right-outline" class="size-5" />
          </NuxtLink>
          <div class="flex items-center gap-2 text-gray-500 text-sm">
            <UIcon name="solar:calendar-broken" class="size-5" />
            <span>{{ props.data.post_1.created_at }}</span>
          </div>
        </div>
      </div>
    </div>

    <div class="col-span-12 lg:col-span-4 space-y-8">
      <CommonsBlogItem
        v-for="item in posts"
        :key="item.id"
        :title="item.name"
        :image="item.image"
        :slug="item.slug"
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
const preview = computed(() => {
  const match = props.data.post_1.content.match(/<p>(.*?)<\/p>/);
  return match ? match[1] : "";
});
const posts = [props.data.post_2, props.data.post_3];
</script>
<template>
  <div v-if="ready" class="grid grid-cols-12 gap-10 container">
    <!-- LEFT BIG POST -->
    <div class="col-span-12 lg:col-span-8 relative" v-motion="leftMotion">
      <NuxtImg
        :src="posts.post_1.image"
        class="w-full h-[450px] object-cover"
      />

      <div
        class="absolute top-[400px] right-0 bg-[#e9e4d6] p-8 w-[85%] shadow-lg"
      >
        <h2 class="text-3xl font-extrabold leading-tight">
          {{ posts.post_1.name }}
        </h2>

        <p class="text-gray-700 mt-4 line-clamp-2">
          {{ preview }}
        </p>

        <div
          class="flex items-center gap-3 mt-3 text-xs font-semibold uppercase justify-between"
        >
          <NuxtLink
            :to="`/blog/${posts.post_1.slug}`"
            class="flex px-6 py-2 bg-blue-600 text-white font-semibold uppercase text-sm rounded hover:bg-blue-700 transition"
          >
            <span>XEM THÊM</span>
            <UIcon name="solar:arrow-right-outline" class="size-5" />
          </NuxtLink>

          <div class="flex items-center gap-2 text-gray-500 text-sm">
            <UIcon name="solar:calendar-broken" class="size-5" />
            <span>{{ posts.post_1.created_at }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- RIGHT POSTS -->
    <div class="col-span-12 lg:col-span-4 space-y-8">
      <div
        v-for="(item, i) in posts_right"
        :key="item.id"
        v-motion="cardMotion(i)"
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
import { ref, computed, onMounted } from "vue";

const props = defineProps({
  data: {
    type: Object,
    default: () => ({}),
  },
});

const posts = props.data?.items || {};

const posts_right = [posts.post_2, posts.post_3];

const preview = computed(() => {
  const match = posts.post_1?.content?.match(/<p>(.*?)<\/p>/);
  return match ? match[1] : "";
});

const ready = ref(false);

onMounted(() => {
  ready.value = true;
});

const leftMotion = {
  initial: { opacity: 0, x: -40 },
  visibleOnce: {
    opacity: 1,
    x: 0,
    transition: { duration: 900 },
  },
};

const cardMotion = (i) => ({
  initial: { opacity: 0, y: 40 },
  visibleOnce: {
    opacity: 1,
    y: 0,
    transition: { duration: 900, delay: i * 150 },
  },
});
</script>
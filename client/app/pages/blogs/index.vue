<template>
  <main class="max-w-7xl mx-auto px-6 py-12">
    <div class="mb-12">
      <h1
        class="text-4xl lg:text-5xl font-bold text-hisotech-blue dark:text-blue-400 mb-4 tracking-tight"
      >
        Bài viết
      </h1>
      <div
        class="flex items-center space-x-2 text-sm text-slate-500 dark:text-slate-400"
      >
        <a class="hover:text-primary" href="#">Trang chủ</a>
        <UIcon name="solar:arrow-right-outline" width="12" height="12" />
        <span class="text-primary font-medium">Bài viết</span>
      </div>
    </div>
    <div class="grid grid-cols-12 gap-10">
      <div class="col-span-12 lg:col-span-8 relative">
        <NuxtImg
          src="https://images.unsplash.com/photo-1517336714731-489689fd1ca8"
          class="w-full h-[450px] object-cover"
        />

        <div
          class="absolute top-[400px] right-0 bg-[#e9e4d6] p-8 w-[85%] shadow-lg"
        >
          <h2 class="text-3xl font-extrabold leading-tight">
            M5 Max MacBook Pro Hands-On: The Expensive Tank of Laptops
          </h2>

          <p class="text-gray-700 mt-4">
            Apple finally has cemented its "good," "better," and "best" tiers
            for its MacBook models.
          </p>

          <div
            class="flex items-center gap-3 mt-3 text-xs font-semibold uppercase justify-between"
          >
            <button
              class="flex px-6 py-2 bg-blue-600 text-white font-semibold uppercase text-sm rounded hover:bg-blue-700 transition"
            >
              <span>XEM THÊM</span>
              <UIcon name="solar:arrow-right-outline" class="size-5" />
            </button>
            <div class="flex items-center gap-2 text-gray-500 text-sm">
              <UIcon name="solar:calendar-broken" class="size-5" />
              <span>08/03/2026</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-span-12 lg:col-span-4 space-y-8">
        <CommonsBlogItem
          v-for="item in blogs_featured"
          :key="item.id"
          :title="item.title"
          :image="item.image"
        />
      </div>
    </div>

    <component
      v-for="(data, name, index) in blogs"
      :key="index"
      :is="blogs.name"
      :data="data"
    />
  </main>
</template>
<script setup lang="ts">
import { onMounted, ref } from "vue";
import { useBlogStore } from "../../stores/blog";
import { resolveComponent } from "vue";

// const ShortcodeBlogPosts = resolveComponent("ShortcodeBlogPosts");

const blogs_featured = [
  {
    id: 1,
    title:
      "Google’s Chatbot Told Man to Give It an Android Body Before Encouraging Suicide, Lawsuit Alleges",
    image: "https://images.unsplash.com/photo-1446776811953-b23d57bd21aa",
  },
  {
    id: 2,
    title: "The Plague That Changed the Course of Game of Thrones’ History",
    image: "https://images.unsplash.com/photo-1520975916090-3105956dac38",
  },
];

const blogs = ref([]);

const blogStore = useBlogStore();

onMounted(async () => {
  await blogStore.fetchPosts();

  const items = blogStore.posts.data;

  function toComponentName(key: string) {
    return (
      "Shortcode" +
      key
        .split("-")
        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
        .join("")
    );
  }

  blogs.value = Object.entries(items).map(([key, value]: any) => ({
    name: toComponentName(key),
    data: value.items,
  }));
  blogs.value = blogs.value.map((item) => ({
    ...item,
    name: resolveComponent(item.name),
  }));
  console.log(blogs.value);

  // blogs.value = items.map((item: any, index: number) => ({
  //   id: index + 1,
  //   title: item.title,
  //   image: item.image,
  // }));
});
</script>
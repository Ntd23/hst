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
    <component
      v-for="(Shortcode, index) in Shortcodes"
      :key="index"
      :is="Shortcode.component"
      :data="Shortcode.data"
      :title="Shortcode.title"
      class="mb-10"
    />
  </main>
</template>
<script setup lang="ts">
import { onMounted, ref, markRaw } from "vue";
import { useBlogStore } from "../../stores/blog";
import { resolveComponent } from "vue";
import { useShortcodeComponents } from "../../composables/useShortcodeComponents"; // bat buoc

const Shortcodes = ref([]);
const blogStore = useBlogStore();
const components = useShortcodeComponents(); // batws buoc

onMounted(async () => {
  await blogStore.fetchPosts(); // api
  const items = blogStore.posts.data; //data api trar ra
  
  Shortcodes.value = Object.entries(items).map(([key, value]: any) => {
    const name =
      "Shortcode" +
      key
        .split("-")
        .map((i: string) => i.charAt(0).toUpperCase() + i.slice(1))
        .join("");
    return {
      component: markRaw(components[name]),
      data: value.items,
      title: value.title,
    };
  });
});
</script>
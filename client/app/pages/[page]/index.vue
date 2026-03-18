<template>
  <div class="">
    <!-- Trả về giao diện Shortcode của Trang thông thường NẾU đang ở route cấp 1 -->
    <CommonsPageRenderer v-if="isSingleSlug" :slug="pageSlug" />
    <NuxtPage v-else />
  </div>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { useRoute } from "vue-router";
import { useEntitySeo } from "~/composables/seo/useEntitySeo";

definePageMeta({ name: 'single-slug-route' })

const route = useRoute();
const pageSlug = computed(() => String(route.params.page || ''));

// Chỉ load PageRenderer khi route chính xác trùng với single-slug-route (ko phải route con)
const isSingleSlug = computed(() => {
  console.log("Current route name:", route.name);
  console.log("Matched routes:", route.matched.map(r => r.name));
  return !route.params.detail;
});

// Load SEO metadata dynamically based on the slug
useEntitySeo(pageSlug.value);
</script>

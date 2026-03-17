<template>
  <div class="">
    <!-- Trả về giao diện Shortcode của Trang thông thường -->
    <CommonsPageRenderer :slug="pageSlug" />
    <NuxtPage/>
  </div>
</template>

<script setup lang="ts">
import { computed, defineAsyncComponent, shallowRef } from "vue";
import { useRoute } from "vue-router";
import { useEntitySeo } from "~/composables/seo/useEntitySeo";

definePageMeta({ name: 'single-slug-route' })

const route = useRoute();
const pageSlug = (route.params.page as string) || '';

// Load SEO metadata dynamically based on the slug
useEntitySeo(pageSlug);
</script>

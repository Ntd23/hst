<template>
  <div v-if="entityType === 'service'" class="detail-page-wrapper">
    <!-- Component Chi tiết Dịch vụ Nằm đây -->
    <component :is="serviceComponent" :slug="slug" :prefix="'service'" :data="entityData" />
  </div>
  <div v-else-if="entityType === 'blog'" class="detail-page-wrapper">
    <component :is="blogComponent" :slug="slug" :prefix="'blog'" :data="entityData" />
  </div>
  <div v-else>
    <!-- Trả về giao diện Shortcode của Trang thông thường -->
    <CommonsPageRenderer :slug="slug" />
  </div>
</template>

<script setup lang="ts">
import { computed, defineAsyncComponent, shallowRef } from "vue";
import { useRoute } from "vue-router";

definePageMeta({ name: 'single-slug-route' })

const route = useRoute();
const slug = (route.params.slug as string) || '';

// Call middleware entity proxy để đếm xem thằng này thuộc mảng "service" hay mảng "blog" hay không
const { data: entityFetch } = await useFetch<any>(`/api/entity/${slug}`);
const entityType = computed(() => entityFetch.value?.type || 'unknown');
const entityData = computed(() => entityFetch.value?.data || null);

// Ánh xạ Component
const serviceComponent = shallowRef<any>(defineAsyncComponent(() => import(`~/components/pages/details/ServiceDetail.vue`)));
const blogComponent = shallowRef<any>(defineAsyncComponent(() => import(`~/components/pages/details/BlogDetail.vue`)));

const titleSlug = slug ? slug.charAt(0).toUpperCase() + slug.slice(1).replace(/-/g, ' ') : 'Homepage';
usePageSeo(computed(() => ({
  title: `${titleSlug} | HISOTECH`,
  description: `${titleSlug} page content.`
})))
</script>

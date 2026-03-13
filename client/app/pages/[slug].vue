<template>
  <CommonsPageRenderer :slug="slug" />
</template>

<script setup lang="ts">
import { computed } from "vue";
import { useRoute } from "vue-router";

definePageMeta({ name: 'single-slug-route' })

const route = useRoute();
const slug = (route.params.slug as string) || '';

const titleSlug = slug ? slug.charAt(0).toUpperCase() + slug.slice(1).replace(/-/g, ' ') : 'Homepage';
usePageSeo(computed(() => ({
  title: `${titleSlug} | HISOTECH`,
  description: `${titleSlug} page content.`
})))
</script>

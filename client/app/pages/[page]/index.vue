<template>
  <div>
    <CommonsPageRenderer v-if="isSingleSlug" :slug="pageSlug" />
    <NuxtPage v-else />
  </div>
</template>

<script setup lang="ts">
import CommonsPageRenderer from "~/components/commons/renderers/PageRenderer.vue";

definePageMeta({ name: "single-slug-route" });

const { pageSlug, isSingleSlug } = useSingleSlugPage();
</script>

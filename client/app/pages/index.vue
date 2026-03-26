<style scoped>
</style>
<template>
  <div class="-mt-[100px]">
    <CommonsPageRenderer slug="homepage" />
    <NuxtPage />
  </div>
</template>

<script setup lang="ts">
import CommonsPageRenderer from "~/components/commons/renderers/PageRenderer.vue";
import { useHomeSeo } from "~/composables/seo/useHomeSeo";

useHomeSeo();
</script>

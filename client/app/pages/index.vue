<style scoped>

</style>
<template>
  <div class="-mt-[100px]">
    <CommonsPageRenderer slug="homepage" />
    <NuxtPage/>
  </div>
</template>

<script setup lang="ts">
import { useHomeSeo } from "~/composables/seo/useHomeSeo";

useHomeSeo();
</script>

<style scoped>
.home {
  margin-top: -70px;
}
</style>
<template>
  <div class="home">
    <CommonsPageRenderer slug="homepage" />
  </div>
</template>

<script setup lang="ts">
import { useHomeSeo } from "~/composables/seo/useHomeSeo";

useHomeSeo();
</script>

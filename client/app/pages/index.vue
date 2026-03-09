<style scoped>
.home {
  margin-top: -70px;
}
</style>
<template>
  <div class="home">
    <!-- Above the fold: load immediately -->
    <HomeSectionsSimpleSlider />
    <HomeSectionsSiteStatistics />
    <HomeSectionsServices />

    <!-- Below the fold: lazy hydration — only hydrate when scrolled into view -->
    <LazyHomeSectionsProducts hydrate-on-visible />
    <LazyHomeSectionsAbout hydrate-on-visible />
    <LazyHomeSectionsTeam hydrate-on-visible />
    <LazyHomeSectionsFaq hydrate-on-visible />
    <LazyHomeSectionsConsult hydrate-on-visible />
    <LazyHomeSectionsNews hydrate-on-visible />
  </div>
</template>

<script setup lang="ts">
import { useHomeSeo } from "~/composables/seo/useHomeSeo";

useHomeSeo();
</script>

<template>
  <div
    class="font-sans antialiased text-slate-700 dark:text-slate-200 bg-pastel-gradient min-h-screen selection:bg-secondary selection:text-white transition-colors duration-300"
  >
    <!-- Above the fold: load immediately -->
    <HomeAppHeader />
    <HomeSectionsHero />
    <HomeSectionsServices />

    <!-- Below the fold: lazy hydration — only hydrate when scrolled into view -->
    <LazyHomeSectionsProducts hydrate-on-visible />
    <LazyHomeSectionsAbout hydrate-on-visible />
    <LazyHomeSectionsTeam hydrate-on-visible />
    <LazyHomeSectionsFaq hydrate-on-visible />
    <LazyHomeSectionsConsult hydrate-on-visible />
    <LazyHomeSectionsNews hydrate-on-visible />
    <LazyHomeAppFooter hydrate-on-visible />
  </div>
</template>

<script setup lang="ts">
import { useHomeSeo } from '~/composables/seo/useHomeSeo'

useHomeSeo()
</script>

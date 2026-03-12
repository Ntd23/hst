<style scoped>
.home {
  margin-top: -70px;
}
</style>
<template>
  <div class="home">
    <template v-for="(section, index) in pageSections" :key="index">
      <component
        :is="getSectionComponent(section.shortcode)"
        :data="section.content"
        v-bind="index >= 3 ? { 'hydrate-on-visible': true } : {}"
      />
    </template>
  </div>
</template>

<script setup lang="ts">
import { useHomeSeo } from "~/composables/seo/useHomeSeo";

useHomeSeo();

const { data: pageData } = await usePageSections<any>('homepage');
const pageSections = computed(() => pageData.value?.sections || []);

const getSectionComponent = (shortcode: string) => {
  if (shortcode === 'simple-slider') return resolveComponent('HomeSectionsSimpleSlider');
  if (shortcode === 'site-statistics') return resolveComponent('HomeSectionsSiteStatistics');
  if (shortcode === 'services') return resolveComponent('HomeSectionsServices');
  if (shortcode === 'include-webdemo') return resolveComponent('HomeSectionsProducts');
  if (shortcode === 'about-us-information') return resolveComponent('HomeSectionsAbout');
  if (shortcode === 'team') return resolveComponent('HomeSectionsTeam');
  if (shortcode === 'faqs') return resolveComponent('HomeSectionsFaq');
  if (shortcode === 'contact-block') return resolveComponent('HomeSectionsConsult');
  if (shortcode === 'blog-posts') return resolveComponent('HomeSectionsNews');
  return 'div';
};
</script>

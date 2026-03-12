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
  if (shortcode === 'simple-slider') return resolveComponent('ShortcodeSimpleSlider');
  if (shortcode === 'site-statistics') return resolveComponent('ShortcodeSiteStatistics');
  if (shortcode === 'services') return resolveComponent('ShortcodeServices');
  if (shortcode === 'include-webdemo') return resolveComponent('ShortcodeProducts');
  if (shortcode === 'about-us-information') return resolveComponent('ShortcodeAbout');
  if (shortcode === 'team') return resolveComponent('ShortcodeTeam');
  if (shortcode === 'faqs') return resolveComponent('ShortcodeFaq');
  if (shortcode === 'contact-block') return resolveComponent('ShortcodeConsult');
  if (shortcode === 'blog-posts') return resolveComponent('ShortcodeNews');
  return 'div';
};
</script>

<template>
  <main class="relative overflow-hidden w-full">
    <template v-if="pageSections.length > 0">
      <component
        v-for="(section, index) in pageSections"
        :key="index"
        :is="getSectionComponent(section.shortcode)"
        :data="section.content"
        v-bind="index >= 3 ? { 'hydrate-on-visible': true } : {}"
      />
    </template>
    <template v-else-if="!pending">
      <div class="py-16 sm:py-24 relative w-full">
        <div class="absolute inset-0 bg-gradient-to-br from-sky-50 via-white to-cyan-50" />
        <UContainer class="relative z-10 w-full">
          <div class="mx-auto max-w-3xl rounded-3xl border border-sky-100 bg-white/90 p-8 shadow-sm sm:p-10">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-primary">
              Laravel Menu Route
            </p>
            <h1 class="mt-3 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">
              {{ menuTitle }}
            </h1>
            <p class="mt-3 text-sm text-slate-500">
              URL: {{ resolvedPath }}
            </p>
            <p class="mt-5 leading-relaxed text-slate-700">
              This route is resolved from Laravel menu data. Backend does not expose page content by slug yet, so this
              page is rendered as a fallback.
            </p>

            <div class="mt-8 flex flex-wrap gap-3">
              <UButton to="/" color="primary" variant="solid">
                Back to Home
              </UButton>
              <UButton
                v-if="menuItem?.url"
                :to="menuItem.url"
                color="neutral"
                variant="outline"
              >
                Reload This URL
              </UButton>
            </div>
          </div>
        </UContainer>
      </div>
    </template>
  </main>
</template>

<script setup lang="ts">
import { resolveComponent as builtinResolveComponent } from 'vue'

type MenuItem = {
  id?: number | string
  title?: string
  url?: string
}

const props = defineProps<{
  menuItem?: MenuItem | null
  resolvedPath: string
}>()

const menuTitle = computed(() => props.menuItem?.title || 'Content In Progress')

const slug = computed(() => {
  let p = props.resolvedPath || ''
  if (p.startsWith('/')) p = p.slice(1)
  if (p.endsWith('/')) p = p.slice(0, -1)
  
  if (!p) return 'homepage'
  return p
})

const { data: pageData, pending } = await usePageSections<any>(slug.value)
const pageSections = computed(() => pageData.value?.sections || [])

const getSectionComponent = (shortcode: string) => {
  if (shortcode === 'simple-slider') return builtinResolveComponent('ShortcodeSimpleSlider');
  if (shortcode === 'site-statistics') return builtinResolveComponent('ShortcodeSiteStatistics');
  if (shortcode === 'services') return builtinResolveComponent('ShortcodeServices');
  if (shortcode === 'include-webdemo') return builtinResolveComponent('ShortcodeProducts');
  if (shortcode === 'about-us-information') return builtinResolveComponent('ShortcodeAbout');
  if (shortcode === 'team') return builtinResolveComponent('ShortcodeTeam');
  if (shortcode === 'faqs') return builtinResolveComponent('ShortcodeFaq');
  if (shortcode === 'contact-block') return builtinResolveComponent('ShortcodeConsult');
  if (shortcode === 'blog-posts') return builtinResolveComponent('ShortcodeNews');
  return 'div';
}
</script>

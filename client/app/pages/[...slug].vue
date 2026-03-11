<template>
  <div v-if="pending" class="flex min-h-[60vh] items-center justify-center">
    <UIcon name="i-lucide-loader-2" class="size-8 animate-spin text-primary" />
  </div>

  <component
    v-else
    :is="resolvedComponent"
    :menu-item="matchedMenuItem"
    :resolved-path="resolvedPath"
  />
</template>

<script setup lang="ts">
import type { Component } from 'vue'
import ContactPage from '~/components/pages/contact-page.vue'
import MenuFallbackPage from '~/components/pages/menu-fallback.vue'
import WebsiteDemoPage from '~/pages/website-demo/index.vue'
import WebsiteDemosPage from '~/pages/website-demos/index.vue'

type MenuItem = {
  id?: number | string
  title?: string
  url?: string
  css_class?: string | null
  reference_id?: number | string | null
  reference_type?: string | null
  children?: MenuItem[]
}

const route = useRoute()
const { locale } = useI18n()
const commonStore = useCommonStore()

const reservedPrefixes = new Set([
  'api',
  'admin',
  'storage',
  '_nuxt',
  '__nuxt',
  'build',
  'vendor',
])

const normalizePath = (value: string | undefined | null): string => {
  if (!value) return '/'

  let path = value.trim()
  if (/^https?:\/\//i.test(path)) {
    try {
      path = new URL(path).pathname || '/'
    } catch {
      path = '/'
    }
  }

  if (!path.startsWith('/')) path = `/${path}`
  path = path.replace(/\/{2,}/g, '/')
  if (path.length > 1) path = path.replace(/\/+$/, '')
  return path.toLowerCase()
}

const stripLocalePrefix = (path: string, currentLocale: string): string => {
  const normalized = normalizePath(path)
  const localePrefix = `/${currentLocale.toLowerCase()}`

  if (normalized === localePrefix) return '/'
  if (normalized.startsWith(`${localePrefix}/`)) return normalized.slice(localePrefix.length)
  return normalized
}

const flattenMenuItems = (items: MenuItem[]): MenuItem[] => {
  return items.flatMap((item) => [item, ...flattenMenuItems(item.children ?? [])])
}

const resolvedPath = computed(() => stripLocalePrefix(route.path, locale.value))
const firstSegment = computed(() => resolvedPath.value.split('/').filter(Boolean)[0] ?? '')

if (firstSegment.value && reservedPrefixes.has(firstSegment.value)) {
  throw createError({
    statusCode: 404,
    statusMessage: 'Not found',
  })
}

const { data: matchedMenuItem, pending } = await useAsyncData<MenuItem | null>(
  `menu-route-${locale.value}-${resolvedPath.value}`,
  async () => {
    await commonStore.fetchHeader(locale.value)
    const menuItems = flattenMenuItems(commonStore.headerData?.main_menu?.items ?? [])
    return menuItems.find((item) => normalizePath(item.url) === resolvedPath.value) ?? null
  },
)

if (!matchedMenuItem.value) {
  throw createError({
    statusCode: 404,
    statusMessage: 'Page not found',
  })
}

const componentByReferenceId: Record<string, Component> = {
  // Keep this map synced with Laravel Botble Page reference IDs.
  '13': WebsiteDemoPage,
  '21': WebsiteDemosPage,
}

const componentByMenuCssClass: Record<string, Component> = {
  // If you set menu item css_class in Laravel admin, map it here.
  'page-contact': ContactPage,
  contact: ContactPage,
}

const contactPathAliases = new Set([
  '/contact',
  '/lien-he',
  '/lien-he-voi-chung-toi',
])

const resolvedComponent = computed<Component>(() => {
  const menuCssClass = matchedMenuItem.value?.css_class?.trim().toLowerCase()
  if (menuCssClass) {
    const component = componentByMenuCssClass[menuCssClass]
    if (component) return component
  }

  const referenceId = matchedMenuItem.value?.reference_id
  if (referenceId !== null && referenceId !== undefined) {
    const component = componentByReferenceId[String(referenceId)]
    if (component) return component
  }

  if (contactPathAliases.has(resolvedPath.value)) {
    return ContactPage
  }

  return MenuFallbackPage
})

const pageTitle = computed(() => matchedMenuItem.value?.title || 'HISOTECH')
usePageSeo(computed(() => ({
  title: `${pageTitle.value} | HISOTECH`,
  description: `Menu route: ${resolvedPath.value}`,
  favicon: commonStore.headerData?.logo?.favicon,
  robots: 'index,follow',
})))
</script>

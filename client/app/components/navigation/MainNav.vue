<!-- app/components/navigation/MainNav.vue -->
<template>
  <header class="border-b bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex h-16 items-center justify-between gap-4">
        <!-- Brand -->
        <NuxtLink to="/" class="font-semibold tracking-tight">
          HST
        </NuxtLink>

        <!-- Desktop nav -->
        <nav class="hidden md:flex items-center gap-1">
          <template v-for="item in items" :key="item.id">
            <!-- Dropdown parent -->
            <div v-if="item.children?.length" class="relative group">
              <button
                type="button"
                class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 hover:text-slate-900"
                aria-haspopup="menu"
              >
                <span>{{ item.title }}</span>
                <svg viewBox="0 0 20 20" class="h-4 w-4" fill="currentColor" aria-hidden="true">
                  <path
                    fill-rule="evenodd"
                    d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 10.94l3.71-3.71a.75.75 0 1 1 1.06 1.06l-4.24 4.24a.75.75 0 0 1-1.06 0L5.21 8.29a.75.75 0 0 1 .02-1.08Z"
                    clip-rule="evenodd"
                  />
                </svg>
              </button>

              <!-- Dropdown -->
              <div
                class="invisible absolute left-0 top-full z-50 mt-2 w-64 rounded-xl border bg-white p-2 shadow-lg opacity-0 transition
                       group-hover:visible group-hover:opacity-100"
              >
                <NavList :items="item.children" />
              </div>
            </div>

            <!-- Leaf item -->
            <template v-else>
              <!-- External -->
              <a
                v-if="isExternal(item.url)"
                :href="item.url"
                :target="item.target"
                rel="noopener"
                class="rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 hover:text-slate-900"
              >
                {{ item.title }}
              </a>

              <!-- Internal -->
              <NuxtLink
                v-else
                :to="toInternalLink(item.url)"
                :target="item.target"
                class="rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 hover:text-slate-900"
                :class="isActive(item.url) ? 'bg-slate-100 text-slate-900' : ''"
              >
                {{ item.title }}
              </NuxtLink>
            </template>
          </template>
        </nav>

        <!-- Mobile -->
        <button
          type="button"
          class="md:hidden inline-flex items-center justify-center rounded-lg border px-3 py-2 text-sm font-medium"
          @click="mobileOpen = !mobileOpen"
          :aria-expanded="mobileOpen"
        >
          {{ mobileOpen ? 'Đóng' : 'Menu' }}
        </button>
      </div>
    </div>

    <!-- Mobile panel -->
    <div v-if="mobileOpen" class="md:hidden border-t bg-white">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-3">
        <div class="space-y-1">
          <MobileNavItem
            v-for="item in items"
            :key="item.id"
            :item="item"
            @navigate="mobileOpen = false"
          />
        </div>
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
import type { NavItem } from '~~/shared/types/navigation'
import NavList from './NavList.vue'
import MobileNavItem from './MobileNavItem.vue'
import { toInternalLink } from '~/utils/url';

defineProps<{ items: NavItem[] }>()

const route = useRoute()
const mobileOpen = ref(false)

// Auto-close mobile when route changes
watch(
  () => route.fullPath,
  () => {
    mobileOpen.value = false
  }
)

/**
 * External link detection:
 * - If url is absolute and origin differs => external
 * - Relative urls => internal
 */
function isExternal(url: string) {
  if (!url || url === '#') return false
  try {
    const u = new URL(url)
    return u.origin !== window.location.origin
  } catch {
    return false
  }
}

function isActive(url: string) {
  const to = toInternalLink(url)
  return to !== '#' && route.path === to
}
</script>

<!-- app/components/navigation/NavList.vue -->
<template>
  <ul class="space-y-1">
    <li v-for="it in items" :key="it.id" class="relative">
      <div v-if="it.children?.length" class="group/sub relative">
        <div class="flex items-center justify-between rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 hover:text-slate-900">
          <span>{{ it.title }}</span>
          <svg viewBox="0 0 20 20" class="h-4 w-4" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 0 1 .02-1.06L10.94 10 7.23 6.29a.75.75 0 1 1 1.06-1.06l4.24 4.24a.75.75 0 0 1 0 1.06l-4.24 4.24a.75.75 0 0 1-1.08.02Z" clip-rule="evenodd"/>
          </svg>
        </div>

        <!-- Submenu -->
        <div
          class="invisible absolute left-full top-0 z-50 ml-2 w-64 rounded-xl border bg-white p-2 shadow-lg opacity-0 transition
                 group-hover/sub:visible group-hover/sub:opacity-100"
        >
          <NavList :items="it.children" />
        </div>
      </div>

      <NuxtLink
        v-else
        :to="toLink(it.url)"
        :target="it.target"
        class="block rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 hover:text-slate-900"
      >
        {{ it.title }}
      </NuxtLink>
    </li>
  </ul>
</template>

<script setup lang="ts">
import type { NavItem } from '~~/shared/types/navigation'

defineProps<{ items: NavItem[] }>()

function toLink(url: string) {
  if (!url || url === '#') return '#'
  try {
    const u = new URL(url)
    return (u.pathname || '/') + (u.search || '')
  } catch {
    return url.startsWith('/') ? url : `/${url}`
  }
}
</script>

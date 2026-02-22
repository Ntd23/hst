<!-- app/components/navigation/MobileNavItem.vue -->
<template>
  <div class="rounded-xl border">
    <div class="flex items-center justify-between px-3 py-2">
      <NuxtLink
        v-if="item.url !== '#'"
        :to="toLink(item.url)"
        :target="item.target"
        class="text-sm font-medium text-slate-800"
      >
        {{ item.title }}
      </NuxtLink>
      <span v-else class="text-sm font-medium text-slate-800">{{ item.title }}</span>

      <button
        v-if="item.children?.length"
        type="button"
        class="rounded-lg px-2 py-1 text-sm text-slate-600 hover:bg-slate-100"
        @click="open = !open"
      >
        {{ open ? '−' : '+' }}
      </button>
    </div>

    <div v-if="item.children?.length && open" class="border-t px-2 py-2">
      <div class="space-y-1">
        <MobileNavItem v-for="c in item.children" :key="c.id" :item="c" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { NavItem } from '~~/shared/types/navigation'

const props = defineProps<{ item: NavItem }>()
const open = ref(false)

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

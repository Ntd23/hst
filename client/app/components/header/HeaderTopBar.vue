<template>
  <div class="border-b bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex flex-wrap items-center justify-between gap-3 py-2 text-sm text-slate-600">
        <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
          <span v-if="data.phone">{{ data.phone }}</span>
          <span v-if="data.email">{{ data.email }}</span>
          <span v-if="data.address">{{ data.address }}</span>
        </div>

        <div class="flex items-center gap-3">
          <slot name="right" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { HeaderTopBarData } from '~~/shared/types/site'

defineProps<{ data: HeaderTopBarData }>()
</script>

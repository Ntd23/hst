<template>
  <main class="relative overflow-hidden py-16 sm:py-24">
    <div class="absolute inset-0 bg-gradient-to-br from-sky-50 via-white to-cyan-50" />
    <UContainer class="relative z-10">
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
  </main>
</template>

<script setup lang="ts">
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
</script>

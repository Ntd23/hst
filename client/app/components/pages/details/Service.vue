<template>
  <main class="relative overflow-hidden w-full">
    <div v-if="pending" class="flex min-h-[60vh] items-center justify-center">
      <UIcon name="i-lucide-loader-2" class="size-8 animate-spin text-primary" />
    </div>

    <template v-else-if="pageData">
      <!-- Breadcrumb -->
      <CommonsAppBreadcrumb
        title="Chi tiết dịch vụ"
        :items="breadcrumbItems"
      />

      <!-- Hero Section -->
      <section class="py-12 sm:py-16 relative overflow-hidden">
        <UContainer>
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
            <!-- Left: Info -->
            <div class="flex flex-col justify-center">
              <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4 leading-tight">
                {{ pageData.name }}
              </h2>
              <p
                v-if="pageData.description"
                class="text-lg text-slate-600 leading-relaxed mb-6"
              >
                {{ pageData.description }}
              </p>
            </div>

            <!-- Right: Image -->
            <div v-if="pageData.image" class="relative">
              <div class="relative w-full aspect-video rounded-2xl overflow-hidden shadow-lg">
                <NuxtImg
                  :src="pageData.image"
                  :alt="pageData.name"
                  class="w-full h-full object-cover"
                />
              </div>
            </div>
          </div>
        </UContainer>
      </section>

      <!-- Shortcode Sections (nếu có) -->
      <template v-if="Shortcodes.length > 0">
        <component
          v-for="(sc, index) in Shortcodes"
          :key="index"
          :is="sc.component"
          :data="sc.data"
          v-bind="index >= 3 ? { 'hydrate-on-visible': true } : {}"
        />
      </template>

      <!-- HTML Content (nếu có, sau khi strip shortcode) -->
      <section v-if="cleanContent" class="py-12 sm:py-16">
        <UContainer>
          <div
            v-html="cleanContent"
            class="prose prose-lg prose-slate max-w-none prose-headings:font-bold prose-p:text-slate-600 prose-img:rounded-xl"
          />
        </UContainer>
      </section>
    </template>

    <div v-else class="py-24 text-center">
      <h1 class="text-2xl font-bold text-gray-800">Page Content Not Found</h1>
      <p class="text-gray-500 mt-2">The requested slug "{{ slug }}" returned no data from the API.</p>
    </div>
  </main>
</template>

<script setup lang="ts">
import { useMappedShortcodes } from '~/composables/useMappedShortcodes'

const props = defineProps<{
  slug: string
}>()

const { Shortcodes, mapSectionsToShortcodes } = useMappedShortcodes()
const { data: pageData, pending } = await usePageDetail<any>(props.slug)

// Map shortcode sections nếu có
if (pageData.value?.sections) {
  mapSectionsToShortcodes(pageData.value.sections)
}

// Content HTML sạch (đã strip shortcode tags ở backend)
const cleanContent = computed(() => {
  const content = pageData.value?.content
  if (!content) return null
  // Kiểm tra content có thực sự có text hay chỉ toàn whitespace
  const stripped = content.replace(/<[^>]*>/g, '').trim()
  return stripped ? content : null
})

// Breadcrumb items
const breadcrumbItems = computed(() => {
  const items: Array<{ label: string; to?: string }> = []
  items.push({ label: pageData.value?.name || props.slug })
  return items
})
</script>

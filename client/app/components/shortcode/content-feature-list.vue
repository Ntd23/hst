<template>
  <section class="space-y-6">
    <div v-if="hasHeader">
      <h3
        v-if="sectionData.title"
        class="text-2xl sm:text-3xl font-bold text-slate-900"
        v-html="sectionData.title"
      ></h3>
      <p
        v-if="sectionData.description"
        class="mt-3 text-slate-600"
        v-html="sectionData.description"
      ></p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div
        v-for="(item, idx) in features"
        :key="idx"
        class="glass-panel p-6 rounded-2xl flex items-start gap-4 hover:-translate-y-1 transition-transform duration-300"
      >
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-primary flex items-center justify-center shrink-0">
          <i v-if="item?.icon" :class="item.icon + ' text-2xl'"></i>
          <UIcon v-else name="i-lucide-sparkles" class="size-5" />
        </div>
        <div>
          <h4 class="font-bold text-slate-800 text-base sm:text-lg" v-html="item?.title"></h4>
          <p v-if="item?.description" class="mt-1 text-sm text-slate-600" v-html="item.description"></p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
const props = defineProps<{
  data?: any
}>()

const sectionData = computed(() => props.data?.shortcode || props.data?.data || props.data || {})
const features = computed(() => props.data?.features || [])
const hasHeader = computed(() => Boolean(sectionData.value?.title || sectionData.value?.description))
</script>
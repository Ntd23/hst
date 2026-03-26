<template>
  <section class="flex flex-col md:flex-row gap-8 items-center bg-white/60 backdrop-blur-md rounded-3xl p-6 sm:p-8 shadow-sm border border-white/80">
    <div class="w-full md:w-1/2">
      <div v-if="sectionData.image" class="rounded-2xl overflow-hidden shadow-lg w-full h-64">
        <NuxtImg
          :src="sectionData.image"
          :alt="sectionData.title || 'Featured image'"
          class="w-full h-full object-cover"
        />
      </div>
    </div>

    <div class="w-full md:w-1/2 space-y-4">
      <h3
        v-if="sectionData.title"
        class="text-2xl font-bold text-slate-900"
        v-html="sectionData.title"
      ></h3>
      <p
        v-if="sectionData.description"
        class="text-slate-600"
        v-html="sectionData.description"
      ></p>

      <ul class="space-y-3">
        <li v-for="(item, idx) in features" :key="idx" class="flex items-start gap-3 text-slate-700">
          <UIcon name="i-lucide-arrow-right-circle" class="size-5 text-primary" />
          <div>
            <span class="font-medium" v-html="item?.title"></span>
            <p v-if="item?.description" class="text-sm text-slate-500" v-html="item.description"></p>
          </div>
        </li>
      </ul>
    </div>
  </section>
</template>

<script setup lang="ts">
const props = defineProps<{
  data?: any
}>()

const { sectionData, features } = useContentFeaturedShortcode(
  toRef(props, "data")
)
</script>

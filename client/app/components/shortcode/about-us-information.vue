<template>
  <section class="py-16 sm:py-24 relative overflow-hidden font-inter">
    <UContainer>
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
        
        <!-- LEFT: Image + Floating Avatar Badge -->
        <div class="relative w-full max-w-lg mx-auto lg:mx-0">
          <!-- Main Image -->
          <div
            v-motion
            :initial="{ opacity: 0, x: -30 }"
            :visible-once="{ opacity: 1, x: 0, transition: { duration: 700, ease: [0.16, 1, 0.3, 1] } }"
            class="relative rounded-[2rem] overflow-hidden shadow-2xl shadow-slate-200/50 aspect-[4/5]"
          >
            <NuxtImg
              v-if="sectionData.image"
              :alt="sectionData.title || 'About Us'"
              class="w-full h-full object-cover"
              :src="sectionData.image"
            />
          </div>

          <!-- Floating Avatar Badge (Top Right) -->
          <div
            v-if="sectionData.image_1 || sectionData.image_2"
            v-motion
            :initial="{ opacity: 0, scale: 0.8 }"
            :visible-once="{ opacity: 1, scale: 1, transition: { duration: 600, delay: 300 } }"
            class="absolute top-8 -right-6 lg:-right-10 bg-white/70 backdrop-blur-xl p-2.5 rounded-full shadow-lg shadow-slate-200/50 border border-white flex items-center"
          >
            <div class="flex -space-x-4">
              <NuxtImg
                v-if="sectionData.image_1"
                class="w-16 h-16 rounded-full border-[3px] border-white object-cover shadow-sm"
                :src="sectionData.image_1"
              />
              <NuxtImg
                v-if="sectionData.image_2"
                class="w-16 h-16 rounded-full border-[3px] border-white object-cover shadow-sm"
                :src="sectionData.image_2"
              />
            </div>
          </div>
        </div>

        <!-- RIGHT: Content & Cards -->
        <div class="flex flex-col justify-center relative">
          <div
            v-motion
            :initial="{ opacity: 0, x: 30 }"
            :visible-once="{ opacity: 1, x: 0, transition: { duration: 700, ease: [0.16, 1, 0.3, 1] } }"
          >
            <!-- Subtitle -->
            <h4
              v-if="sectionData.subtitle"
              class="text-primary font-bold tracking-wider uppercase text-sm mb-4"
              v-html="sectionData.subtitle"
            />

            <!-- Title -->
            <h2
              v-if="sectionData.title"
              class="text-3xl sm:text-4xl lg:text-4xl font-extrabold text-slate-900 tracking-tight mb-6 leading-tight"
              v-html="sectionData.title"
            />
            
            <!-- Description -->
            <p
              v-if="sectionData.description"
              class="text-slate-600 text-base sm:text-lg mb-10 leading-relaxed"
              v-html="sectionData.description"
            />

            <!-- Bento Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-10" v-if="tabs.length">
              <div
                v-for="(tab, idx) in tabs.slice(0, 2)"
                :key="idx"
                class="bg-[#f2f9ff]/80 backdrop-blur-md rounded-[24px] p-6 lg:p-8 border border-white/60 shadow-sm hover:shadow-md transition-shadow"
              >
                <!-- Glass Icon Box -->
                <div class="w-14 h-14 rounded-[16px] bg-white border border-slate-100 flex items-center justify-center mb-6 shadow-sm">
                  <UIcon :name="tab.icon || 'i-lucide-check-circle'" class="size-6 text-slate-700" />
                </div>
                <!-- Texts -->
                <h3 class="font-bold text-lg text-slate-900 mb-3" v-html="tab.title" />
                <p class="text-sm text-slate-500 leading-relaxed" v-if="tab.description" v-html="tab.description" />
              </div>
            </div>

            <!-- Button / Action -->
            <div v-if="sectionData.button_label" class="pt-2">
              <NuxtLink
                :to="sectionData.button_url || '#'"
                class="inline-flex items-center gap-2 px-8 py-3.5 rounded-full bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm transition-all shadow-md hover:shadow-lg"
              >
                {{ sectionData.button_label }}
                <UIcon name="i-lucide-arrow-right" class="size-4" />
              </NuxtLink>
            </div>
          </div>
        </div>

      </div>
    </UContainer>
  </section>
</template>

<script setup lang="ts">
const props = defineProps<{
  data?: any
}>()

const sectionData = computed(() => {
  const d = props.data?.data || props.data || {};
  return {
    ...props.data, 
    ...d          
  }
})

const tabs = computed(() => sectionData.value?.tabs || [])
</script>

<style scoped>
.font-inter {
  font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
}
</style>

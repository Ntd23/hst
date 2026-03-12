<template>
  <section class="py-16 bg-slate-50/50 dark:bg-slate-900/50">
    <UContainer>
      <div
        v-motion
        :initial="{ opacity: 0, y: 30 }"
        :visible-once="{ opacity: 1, y: 0, transition: { duration: 600 } }"
        class="text-center mb-10 sm:mb-16"
      >
        <span v-if="sectionData.subtitle" class="text-primary font-semibold tracking-wide uppercase text-sm" v-html="sectionData.subtitle"></span>
        <h2 v-if="sectionData.title" class="mt-2 text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white" v-html="sectionData.title">
        </h2>
      </div>

      <!-- Photo Cards: phone=1col, tablet/desktop=side by side -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 sm:gap-8 max-w-4xl mx-auto">
        <div
          v-for="(member, i) in team"
          :key="member.id || member.name"
          v-motion
          :initial="{ opacity: 0, x: Number(i) % 2 === 0 ? -40 : 40 }"
          :visible-once="{ opacity: 1, x: 0, transition: { duration: 600, delay: Number(i) * 100 } }"
          class="group"
        >
          <div class="card-hover-glow rounded-2xl overflow-hidden shadow-lg transition-all duration-400 bg-white dark:bg-slate-800 h-full flex flex-col">
            <!-- Photo -->
            <div class="h-52 sm:h-56 md:h-64 lg:h-72 overflow-hidden relative">
              <NuxtImg
                v-if="member.photo || member.image"
                :alt="member.name"
                :src="member.photo || member.image"
                format="webp"
                loading="lazy"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
              />
              <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent" />
              <div class="absolute bottom-3 sm:bottom-4 left-4 sm:left-5 right-4 sm:right-5">
                <h3 class="text-lg sm:text-xl font-bold text-white">{{ member.name }}</h3>
                <p class="text-primary-300 text-xs sm:text-sm font-medium">{{ member.title || member.role }}</p>
              </div>
            </div>

            <!-- Info -->
            <div class="p-4 sm:p-5 flex-1 flex flex-col">
              <div class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed mb-3 sm:mb-4 line-clamp-3 flex-1" v-html="member.content || member.bio">
              </div>
              <div class="flex items-center justify-between mt-auto">
                <ULink class="text-sm font-semibold text-primary hover:text-secondary transition-colors" :to="member.url || '#'">
                  Liên hệ
                </ULink>
                <div class="flex gap-2" v-if="member.socials">
                  <a
                    v-for="(link, network) in member.socials"
                    :key="network"
                    :href="link"
                    target="_blank"
                    v-show="link"
                    class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-500 hover:bg-primary hover:text-white transition-all duration-200"
                  >
                    <UIcon :name="`i-simple-icons-${String(network).toLowerCase()}`" class="size-4" />
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-10 sm:mt-12 text-center" v-if="sectionData.button?.label">
        <UButton
          :to="sectionData.button.url"
          color="primary"
          variant="solid"
          trailing-icon="i-lucide-arrow-right"
          class="w-full sm:w-auto px-6 sm:px-8 py-3 rounded-xl font-bold btn-primary-lift"
        >
           {{ sectionData.button.label }}
        </UButton>
      </div>
    </UContainer>
  </section>
</template>

<script setup lang="ts">
const props = defineProps<{
  data?: any
}>()

const sectionData = computed(() => props.data || {})
const team = computed(() => props.data?.items || [])
</script>

<template>
  <section class="py-16">
    <UContainer>
      <!-- Header -->
      <div
        v-motion
        :initial="{ opacity: 0, x: -40 }"
        :visible-once="{ opacity: 1, x: 0, transition: { duration: 600 } }"
        class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4 mb-8 sm:mb-10"
      >
        <div>
          <span class="text-secondary font-semibold tracking-wide uppercase text-sm">{{ $t("news.subtitle") }}</span>
          <h2 class="mt-2 text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">
            {{ $t("news.title") }}
          </h2>
        </div>
        <ULink
          class="hidden md:flex items-center text-primary font-medium hover:text-secondary transition-colors"
          to="/blog"
        >
          {{ $t("news.viewAll") }}
          <UIcon name="i-lucide-arrow-right" class="size-4 ml-1" />
        </ULink>
      </div>

      <!-- LISTING MODE: Trang /blog – hiển thị tất cả bài dạng grid -->
      <div v-if="isListingMode" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <NuxtLink
          v-for="article in articles"
          :key="article.id"
          :to="article.url || '/blog'"
          class="card-hover-glow rounded-2xl overflow-hidden group cursor-pointer bg-white/70 backdrop-blur-sm dark:bg-slate-800/70 flex flex-col"
        >
          <!-- Thumbnail -->
          <div class="aspect-video bg-slate-100 relative overflow-hidden">
            <NuxtImg
              v-if="article.image"
              :src="article.image"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
            />
            <div v-else class="w-full h-full bg-gradient-to-br from-primary/20 to-secondary/20" />
          </div>
          <!-- Content -->
          <div class="p-5 flex flex-col flex-1">
            <div class="flex flex-wrap gap-1 mb-3">
              <UBadge
                v-for="cat in article.categories"
                :key="cat.id"
                color="primary"
                variant="subtle"
                size="xs"
              >
                {{ cat.name }}
              </UBadge>
            </div>
            <NuxtLink :to="article.url || '/blog'" class="text-base font-bold text-slate-900 dark:text-white mb-2 line-clamp-2 group-hover:text-primary transition-colors">
              {{ article.name }}
            </NuxtLink>
            <p class="text-sm text-slate-500 line-clamp-2 flex-1 mb-3">{{ article.description }}</p>
            <div class="flex items-center justify-between mt-auto pt-3 border-t border-slate-100 dark:border-slate-700">
              <span class="text-xs text-slate-400">{{ formatDate(article.created_at) }}</span>
              <span class="text-xs text-slate-400">{{ article.author }}</span>
            </div>
          </div>
        </NuxtLink>
      </div>

      <!-- PREVIEW MODE: Trang chủ – featured + sidebar -->
      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 sm:gap-6 lg:gap-8">
        <!-- Featured article (first item) -->
        <div
          v-if="articles.length"
          class="sm:col-span-2 lg:col-span-3 card-hover-glow rounded-2xl overflow-hidden relative group cursor-pointer"
        >
          <div class="h-60 sm:h-72 lg:h-96 bg-slate-900 relative flex items-end">
            <NuxtImg
              v-if="articles[0]?.image"
              :src="articles[0].image"
              class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:opacity-70 transition-opacity"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent" />
            <div class="relative p-5 sm:p-6 lg:p-8 w-full">
              <UBadge color="primary" variant="subtle" size="sm" class="mb-2 sm:mb-3">
                {{ articles[0]?.categories?.[0]?.name ?? $t('common.defaultCategory') }}
              </UBadge>
              <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-white mb-1 sm:mb-2">
                {{ articles[0]?.name }}
              </h3>
              <p class="text-sm text-white/70 mb-2 sm:mb-3 line-clamp-2 hidden sm:block">
                {{ articles[0]?.description }}
              </p>
              <div class="flex items-center justify-between">
                <span class="text-xs text-white/50">{{ formatDate(articles[0]?.created_at) }}</span>
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-white/10 backdrop-blur-sm flex items-center justify-center text-white">
                  <UIcon name="i-lucide-arrow-up-right" class="size-4 sm:size-5" />
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Side list articles -->
        <div class="sm:col-span-2 lg:col-span-2 flex flex-col gap-4 sm:gap-5">
          <NuxtLink
            v-for="article in articles.slice(1, 4)"
            :key="article.id"
            :to="article.url || '/blog'"
            class="card-hover-glow rounded-2xl overflow-hidden flex flex-row group cursor-pointer bg-white/70 backdrop-blur-sm dark:bg-slate-800/70 flex-1"
          >
            <div class="w-24 sm:w-28 lg:w-32 shrink-0 bg-slate-900 relative overflow-hidden">
              <NuxtImg
                v-if="article.image"
                :src="article.image"
                class="absolute inset-0 w-full h-full object-cover"
              />
              <div v-else class="absolute inset-0 bg-gradient-to-br from-primary/30 to-secondary/30" />
            </div>
            <div class="p-3 sm:p-4 flex flex-col justify-center flex-1 min-w-0">
              <span class="text-xs font-bold text-primary uppercase mb-1">
                {{ article.categories?.[0]?.name ?? $t('common.defaultCategory') }}
              </span>
              <h3 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white mb-1 line-clamp-2 group-hover:text-primary transition-colors">
                {{ article.name }}
              </h3>
              <span class="text-xs text-slate-400">{{ formatDate(article.created_at) }}</span>
            </div>
          </NuxtLink>
        </div>
      </div>

      <div class="mt-6 sm:mt-8 md:hidden">
        <ULink
          class="inline-flex items-center text-primary font-medium hover:text-secondary transition-colors"
          to="/blog"
        >
          {{ $t('common.viewAllNews') }}
          <UIcon name="i-lucide-arrow-right" class="size-4 ml-1" />
        </ULink>
      </div>
    </UContainer>
  </section>
</template>

<style scoped>
section {
  font-family: var(--font-body, sans-serif);
}
h2, h3, .text-secondary, .text-primary {
  font-family: var(--font-tech, sans-serif);
}
</style>

<script setup lang="ts">
const props = defineProps<{
  data?: any
}>()

const articles = computed(() => props.data?.items ?? [])

// Tự động phát hiện: nếu API trả > 4 bài thì đang ở trang listing (/blog)
// Nếu <= 4 bài thì đang ở trang chủ (preview mode)
const isListingMode = computed(() => articles.value.length > 4)

function formatDate(dateStr?: string) {
  if (!dateStr) return ''
  try {
    return new Date(dateStr).toLocaleDateString('vi-VN', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric',
    })
  } catch {
    return dateStr
  }
}
</script>

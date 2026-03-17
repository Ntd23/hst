<template>
  <main class="relative overflow-hidden w-full pb-16 bg-slate-50 min-h-screen">
    <!-- Breadcrumb -->
    <CommonsAppBreadcrumb
      :title="pageTitle"
      :items="[{ label: pageTitle }]"
    />

    <UContainer class="pt-8">
      <div v-if="pending" class="flex min-h-[40vh] items-center justify-center">
        <UIcon name="i-lucide-loader-2" class="size-8 animate-spin text-primary" />
      </div>

      <div v-else-if="error" class="py-24 text-center">
        <h2 class="text-2xl font-bold text-slate-800">Không thể tải dữ liệu</h2>
        <p class="text-slate-500 mt-2">{{ error.message }}</p>
      </div>

      <div v-else class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- 1. Cột Trái: Danh sách Post (lg:col-span-8) -->
        <div class="lg:col-span-8 flex flex-col gap-6">
          <div v-if="!posts.length" class="text-center py-12 bg-white rounded-2xl shadow-sm border border-slate-100">
            <UIcon name="i-lucide-inbox" class="size-12 mx-auto text-slate-300 mb-3" />
            <p class="text-slate-500">Chưa có bài viết nào trong mục này.</p>
          </div>
          
          <NuxtLink
            v-for="post in posts"
            :key="post.id"
            :to="post.url || `/blog/${post.slug}`"
            class="group flex flex-col md:flex-row bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 border border-slate-100"
          >
            <!-- Thumbnail -->
            <div class="md:w-5/12 aspect-[4/3] relative overflow-hidden shrink-0">
              <NuxtImg
                v-if="post.image"
                :src="post.image"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              />
              <div v-else class="w-full h-full bg-slate-100 flex items-center justify-center">
                <UIcon name="i-lucide-image" class="size-8 text-slate-300" />
              </div>

              <!-- Badge Danh mục -->
              <div v-if="post.categories?.length" class="absolute top-4 left-4">
                <UBadge color="primary" variant="solid" class="shadow-sm truncate max-w-[150px]">
                  {{ post.categories[0].name }}
                </UBadge>
              </div>
            </div>

            <!-- Content -->
            <div class="p-5 md:p-6 flex flex-col justify-center flex-1">
              <h3 class="text-xl font-bold text-slate-900 group-hover:text-primary transition-colors line-clamp-2 mb-3 leading-snug">
                {{ post.name }}
              </h3>
              
              <p class="text-slate-600 text-sm line-clamp-3 mb-4 leading-relaxed flex-1">
                {{ post.description }}
              </p>

              <div class="flex items-center justify-between mt-auto pt-4 border-t border-slate-100">
                <div class="flex items-center text-sm font-semibold text-primary group-hover:text-secondary tracking-wide transition-colors">
                  XEM THÊM
                  <UIcon name="i-lucide-arrow-right" class="size-4 ml-1" />
                </div>
                <div class="flex items-center text-xs text-slate-500 font-medium">
                  <UIcon name="i-lucide-calendar" class="size-3.5 mr-1" />
                  {{ formatDate(post.created_at) }}
                </div>
              </div>
            </div>
          </NuxtLink>

          <!-- Phân trang (Pagination) -->
          <div v-if="pagination && pagination.last_page > 1" class="flex justify-center mt-6">
            <UPagination
              v-model="currentPage"
              :page-count="pagination.per_page"
              :total="pagination.total"
              :max="5"
              show-first
              show-last
            />
          </div>
        </div>

        <!-- 2. Cột Phải: Sidebar (lg:col-span-4) -->
        <aside class="lg:col-span-4 flex flex-col gap-8">
          
          <!-- Search Box -->
          <div class="bg-white rounded-2xl p-1 shadow-sm border border-slate-100">
            <UInput
              v-model="searchQuery"
              icon="i-heroicons-magnifying-glass-20-solid"
              color="white"
              variant="none"
              placeholder="Tìm kiếm..."
              class="w-full h-11"
              @keyup.enter="handleSearch"
            />
          </div>

          <!-- Danh mục (Categories) -->
          <div v-if="categories.length" class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <h3 class="text-lg font-bold tracking-tight text-slate-900 mb-5 pb-3 border-b border-primary/20 flex flex-col">
              Danh mục
              <div class="w-8 h-1 bg-primary mt-3 rounded-full"></div>
            </h3>
            <ul class="space-y-3">
              <li v-for="cat in categories" :key="cat.id">
                <div
                  @click="toggleCategory(cat.slug)"
                  class="flex items-center justify-between group cursor-pointer"
                >
                  <div class="flex items-center gap-2 text-slate-600 group-hover:text-primary transition-colors">
                    <UIcon name="i-lucide-arrow-right" class="size-3 text-slate-300 group-hover:text-primary transition-colors" />
                    <span :class="{'font-bold text-primary': selectedCategory === cat.slug}" class="text-[15px] hover:underline underline-offset-4 decoration-primary/30">{{ cat.name }}</span>
                  </div>
                  <span class="text-xs font-semibold text-slate-400">({{ cat.posts_count }})</span>
                </div>
              </li>
            </ul>
          </div>

          <!-- Bài viết mới nhất (Recent Posts) -->
          <div v-if="recentPosts.length" class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <h3 class="text-lg font-bold tracking-tight text-slate-900 mb-5 pb-3 border-b border-primary/20 flex flex-col">
              Bài viết mới
              <div class="w-8 h-1 bg-primary mt-3 rounded-full"></div>
            </h3>
            <div class="flex flex-col gap-4">
              <NuxtLink
                v-for="recent in recentPosts"
                :key="recent.id"
                :to="recent.url || `/blog/${recent.slug}`"
                class="flex gap-4 group cursor-pointer"
              >
                <div class="w-20 aspect-square rounded-xl overflow-hidden shrink-0 bg-slate-100 relative">
                  <NuxtImg
                    v-if="recent.image"
                    :src="recent.image"
                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                  />
                </div>
                <div class="flex flex-col flex-1 pb-1">
                  <h4 class="text-sm font-bold text-slate-800 line-clamp-2 leading-snug group-hover:text-primary transition-colors">
                    {{ recent.name }}
                  </h4>
                  <div class="mt-auto text-[11px] font-medium text-slate-400 flex items-center gap-1 uppercase tracking-wider">
                    {{ formatDate(recent.created_at) }}
                  </div>
                </div>
              </NuxtLink>
            </div>
          </div>

          <!-- Tags -->
          <div v-if="tags.length" class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <h3 class="text-lg font-bold tracking-tight text-slate-900 mb-5 pb-3 border-b border-primary/20 flex flex-col">
              Tags
              <div class="w-8 h-1 bg-primary mt-3 rounded-full"></div>
            </h3>
            <div class="flex flex-wrap gap-2">
              <UBadge
                v-for="tag in tags"
                :key="tag.id"
                @click="toggleTag(tag.slug)"
                :class="selectedTag === tag.slug ? 'bg-primary text-white' : 'bg-slate-100/80 text-primary hover:bg-slate-200 cursor-pointer transition-colors border border-slate-200/50 shadow-none font-medium'"
                variant="solid"
              >
                #{{ tag.name }}
              </UBadge>
            </div>
          </div>
        </aside>
      </div>
    </UContainer>
  </main>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { useEntitySeo } from '~/composables/seo/useEntitySeo'

// Config page
definePageMeta({ name: 'blog-listing' })

// Mượn SEO của menu "Blog"
useEntitySeo('blog')

const { locale } = useI18n()
const route = useRoute()
const router = useRouter()

const pageTitle = computed(() => 'Tin tức') // Có thể sync với i18n
const limit = 8

// Reactive state filters
const currentPage = ref(Number(route.query.page) || 1)
const searchQuery = ref(route.query.q as string || '')
const selectedCategory = ref(route.query.category as string || '')
const selectedTag = ref(route.query.tag as string || '')

// Fetch Data từ Backend
const { data: apiResponse, pending, error, refresh } = await useFetch<any>('/api/blog/listing', {
  baseURL: useRuntimeConfig().public.apiBase,
  query: computed(() => ({
    locale: locale.value,
    limit,
    page: currentPage.value,
    q: searchQuery.value || undefined,
    category: selectedCategory.value || undefined,
    tag: selectedTag.value || undefined,
  })),
  watch: false // Tự điều khiển refresh để tránh dội API
})

// Bóc tách dữ liệu
const posts = computed(() => apiResponse.value?.data?.posts?.items ?? [])
const pagination = computed(() => apiResponse.value?.data?.posts)
const categories = computed(() => apiResponse.value?.data?.sidebar?.categories ?? [])
const recentPosts = computed(() => apiResponse.value?.data?.sidebar?.recent_posts ?? [])
const tags = computed(() => apiResponse.value?.data?.sidebar?.tags ?? [])

// Hành động Filter
const syncUrl = () => {
  router.replace({
    query: {
      ...route.query,
      page: currentPage.value > 1 ? currentPage.value : undefined,
      q: searchQuery.value || undefined,
      category: selectedCategory.value || undefined,
      tag: selectedTag.value || undefined,
    }
  })
  refresh()
}

const handleSearch = () => {
  currentPage.value = 1
  syncUrl()
}

const toggleCategory = (slug: string) => {
  selectedCategory.value = selectedCategory.value === slug ? '' : slug
  currentPage.value = 1
  syncUrl()
}

const toggleTag = (slug: string) => {
  selectedTag.value = selectedTag.value === slug ? '' : slug
  currentPage.value = 1
  syncUrl()
}

watch(currentPage, () => {
  syncUrl()
  window.scrollTo({ top: 0, behavior: 'smooth' })
})

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

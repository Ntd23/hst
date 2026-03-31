<template>
<<<<<<< HEAD
  <main class="relative min-h-screen w-full overflow-hidden pb-16">
    <CommonsAppBreadcrumb :title="pageTitle" :items="[{ label: pageTitle }]" />

    <UContainer class="pt-8">
      <div v-if="loading" class="grid grid-cols-1 gap-8 lg:grid-cols-12">
=======
  <main class="relative min-h-screen w-full overflow-hidden bg-slate-50 pb-16">
    <CommonsAppBreadcrumb :title="pageTitle" :items="[{ label: pageTitle }]" />

    <UContainer class="pt-8">
      <div v-if="pending" class="grid grid-cols-1 gap-8 lg:grid-cols-12">
>>>>>>> origin/main
        <div class="space-y-6 lg:col-span-8">
          <div
            v-for="index in 3"
            :key="`blog-list-skeleton-${index}`"
            class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm"
          >
            <div class="aspect-[16/8] animate-pulse bg-slate-200/80 md:hidden" />
            <div class="flex flex-col md:flex-row">
              <div class="hidden animate-pulse bg-slate-200/80 md:block md:w-5/12 md:aspect-[4/3]" />
              <div class="flex-1 space-y-4 p-5 md:p-6">
                <div class="h-6 w-2/3 animate-pulse rounded-xl bg-slate-200/80" />
                <div class="h-4 w-full animate-pulse rounded-xl bg-slate-200/70" />
                <div class="h-4 w-5/6 animate-pulse rounded-xl bg-slate-200/70" />
                <div class="h-4 w-4/6 animate-pulse rounded-xl bg-slate-200/70" />
              </div>
            </div>
          </div>
        </div>

        <aside class="flex flex-col gap-8 lg:col-span-4">
          <div class="h-11 animate-pulse rounded-2xl border border-slate-100 bg-white shadow-sm" />
          <div class="h-72 animate-pulse rounded-2xl border border-slate-100 bg-white shadow-sm" />
          <div class="h-72 animate-pulse rounded-2xl border border-slate-100 bg-white shadow-sm" />
        </aside>
      </div>

      <div v-else-if="error" class="py-24 text-center">
        <h2 class="text-2xl font-bold text-slate-800">{{ loadErrorTitle }}</h2>
        <p class="mt-2 text-slate-500">{{ error.message }}</p>
      </div>

      <div v-else class="grid grid-cols-1 gap-8 lg:grid-cols-12">
        <div class="flex flex-col gap-6 lg:col-span-8">
          <div v-if="!posts.length" class="rounded-2xl border border-slate-100 bg-white py-12 text-center shadow-sm">
            <UIcon name="i-lucide-inbox" class="mx-auto mb-3 size-12 text-slate-300" />
            <p class="text-slate-500">{{ emptyPostsLabel }}</p>
          </div>

          <NuxtLink
            v-for="post in posts"
            :key="post.id"
            :to="post.url || `/blog/${post.slug}`"
            class="group flex flex-col overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition-shadow duration-300 hover:shadow-md md:flex-row"
          >
            <div class="relative aspect-[4/3] shrink-0 overflow-hidden md:w-5/12">
              <NuxtImg
                v-if="post.image"
                :src="post.image"
                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
              />
              <div v-else class="flex h-full w-full items-center justify-center bg-slate-100">
                <UIcon name="i-lucide-image" class="size-8 text-slate-300" />
              </div>

              <div v-if="post.categories?.length" class="absolute left-4 top-4">
                <UBadge color="primary" variant="solid" class="max-w-[150px] truncate shadow-sm">
<<<<<<< HEAD
                  {{ decodeHtml(post.categories[0].name) }}
=======
                  {{ post.categories[0].name }}
>>>>>>> origin/main
                </UBadge>
              </div>
            </div>

            <div class="flex flex-1 flex-col justify-center p-5 md:p-6">
              <h3 class="mb-3 line-clamp-2 text-xl font-bold leading-snug text-slate-900 transition-colors group-hover:text-primary">
<<<<<<< HEAD
                {{ decodeHtml(post.name) }}
              </h3>

              <p class="mb-4 flex-1 line-clamp-3 text-sm leading-relaxed text-slate-600">
                {{ decodeHtml(post.description) }}
=======
                {{ post.name }}
              </h3>

              <p class="mb-4 flex-1 line-clamp-3 text-sm leading-relaxed text-slate-600">
                {{ post.description }}
>>>>>>> origin/main
              </p>

              <div class="mt-auto flex items-center justify-between border-t border-slate-100 pt-4">
                <div class="flex items-center text-sm font-semibold tracking-wide text-primary transition-colors group-hover:text-secondary">
                  {{ readMoreLabel }}
                  <UIcon name="i-lucide-arrow-right" class="ml-1 size-4" />
                </div>
                <div class="flex items-center text-xs font-medium text-slate-500">
                  <UIcon name="i-lucide-calendar" class="mr-1 size-3.5" />
                  {{ formatDate(post.created_at) }}
                </div>
              </div>
            </div>
          </NuxtLink>

          <div v-if="pagination && pagination.last_page > 1" class="mt-6 flex justify-center">
            <UPagination
<<<<<<< HEAD
              :page="currentPage"
              :items-per-page="pagination.per_page"
=======
              v-model="currentPage"
              :page-count="pagination.per_page"
>>>>>>> origin/main
              :total="pagination.total"
              :max="5"
              show-first
              show-last
<<<<<<< HEAD
              @update:page="handlePageChange"
=======
>>>>>>> origin/main
            />
          </div>
        </div>

        <aside class="flex flex-col gap-8 lg:col-span-4">
          <CommonsSidebarWidgets v-if="sidebarWidgets.length" :widgets="sidebarWidgets" />
          <template v-else>
            <div class="rounded-2xl border border-slate-100 bg-white p-1 shadow-sm">
              <UInput
                v-model="searchQuery"
                icon="i-heroicons-magnifying-glass-20-solid"
                color="white"
                variant="none"
                :placeholder="searchPlaceholder"
                class="h-11 w-full"
                @keyup.enter="handleSearch"
              />
            </div>

            <div v-if="categories.length" class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
              <h3 class="mb-5 flex flex-col border-b border-primary/20 pb-3 text-lg font-bold tracking-tight text-slate-900">
                {{ categoriesLabel }}
                <div class="mt-3 h-1 w-8 rounded-full bg-primary"></div>
              </h3>
              <ul class="space-y-3">
                <li v-for="cat in categories" :key="cat.id">
                  <div @click="toggleCategory(cat.slug)" class="group flex cursor-pointer items-center justify-between">
                    <div class="flex items-center gap-2 text-slate-600 transition-colors group-hover:text-primary">
                      <UIcon name="i-lucide-arrow-right" class="size-3 text-slate-300 transition-colors group-hover:text-primary" />
<<<<<<< HEAD
                      <span :class="{ 'font-bold text-primary': selectedCategory === cat.slug }" class="text-[15px] decoration-primary/30 underline-offset-4 hover:underline">{{ decodeHtml(cat.name) }}</span>
=======
                      <span :class="{ 'font-bold text-primary': selectedCategory === cat.slug }" class="text-[15px] decoration-primary/30 underline-offset-4 hover:underline">{{ cat.name }}</span>
>>>>>>> origin/main
                    </div>
                    <span class="text-xs font-semibold text-slate-400">({{ cat.posts_count }})</span>
                  </div>
                </li>
              </ul>
            </div>

            <div v-if="recentPosts.length" class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
              <h3 class="mb-5 flex flex-col border-b border-primary/20 pb-3 text-lg font-bold tracking-tight text-slate-900">
                {{ recentPostsLabel }}
                <div class="mt-3 h-1 w-8 rounded-full bg-primary"></div>
              </h3>
              <div class="flex flex-col gap-4">
                <NuxtLink
                  v-for="recent in recentPosts"
                  :key="recent.id"
                  :to="recent.url || `/blog/${recent.slug}`"
                  class="group flex cursor-pointer gap-4"
                >
                  <div class="relative aspect-square w-20 shrink-0 overflow-hidden rounded-xl bg-slate-100">
                    <NuxtImg
                      v-if="recent.image"
                      :src="recent.image"
                      class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                    />
                  </div>
                  <div class="flex flex-1 flex-col pb-1">
                    <h4 class="line-clamp-2 text-sm font-bold leading-snug text-slate-800 transition-colors group-hover:text-primary">
<<<<<<< HEAD
                      {{ decodeHtml(recent.name) }}
=======
                      {{ recent.name }}
>>>>>>> origin/main
                    </h4>
                    <div class="mt-auto flex items-center gap-1 text-[11px] font-medium uppercase tracking-wider text-slate-400">
                      {{ formatDate(recent.created_at) }}
                    </div>
                  </div>
                </NuxtLink>
              </div>
            </div>

            <div v-if="tags.length" class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
              <h3 class="mb-5 flex flex-col border-b border-primary/20 pb-3 text-lg font-bold tracking-tight text-slate-900">
                {{ tagsLabel }}
                <div class="mt-3 h-1 w-8 rounded-full bg-primary"></div>
              </h3>
              <div class="flex flex-wrap gap-2">
                <UBadge
                  v-for="tag in tags"
                  :key="tag.id"
                  @click="toggleTag(tag.slug)"
                  :class="selectedTag === tag.slug ? 'bg-primary text-white' : 'cursor-pointer border border-slate-200/50 bg-slate-100/80 font-medium text-primary shadow-none transition-colors hover:bg-slate-200'"
                  variant="solid"
                >
                  #{{ decodeHtml(tag.name) }}
                </UBadge>
              </div>
            </div>
          </template>
        </aside>
      </div>
    </UContainer>
  </main>
</template>

<script setup lang="ts">
import CommonsAppBreadcrumb from "~/components/commons/navigation/AppBreadcrumb.vue";
import CommonsSidebarWidgets from "~/components/commons/renderers/SidebarWidgets.vue";

definePageMeta({ name: "blog-listing" });

const { translate, localeCode } = useI18nText();
<<<<<<< HEAD
const { decodeHtml } = useDecodeHtml();
=======
>>>>>>> origin/main
const { siteUrl, canonicalUrl } = useSeoContext();
const {
  pageTitle,
  currentPage,
<<<<<<< HEAD
  loading,
=======
>>>>>>> origin/main
  searchQuery,
  selectedCategory,
  selectedTag,
  pending,
  error,
  posts,
  pagination,
  categories,
  recentPosts,
  tags,
  sidebarWidgets,
<<<<<<< HEAD
  handlePageChange,
=======
>>>>>>> origin/main
  handleSearch,
  toggleCategory,
  toggleTag,
  formatDate,
} = await useBlogListingPage();

const loadErrorTitle = computed(() =>
  translate("blogListing.loadError", localeCode.value === "en" ? "Unable to load data" : "Không thể tải dữ liệu")
);
const emptyPostsLabel = computed(() =>
  translate("blogListing.empty", localeCode.value === "en" ? "There are no posts in this category yet." : "Chưa có bài viết nào trong mục này.")
);
const readMoreLabel = computed(() =>
  translate("news.readMore", localeCode.value === "en" ? "Read more" : "Xem thêm")
);
const searchPlaceholder = computed(() =>
  translate("blogListing.searchPlaceholder", localeCode.value === "en" ? "Search..." : "Tìm kiếm...")
);
const categoriesLabel = computed(() =>
  translate("blogListing.categories", localeCode.value === "en" ? "Categories" : "Danh mục")
);
const recentPostsLabel = computed(() =>
  translate("blogListing.recentPosts", localeCode.value === "en" ? "Recent posts" : "Bài viết mới")
);
const tagsLabel = computed(() => translate("blogDetail.tags", localeCode.value === "en" ? "Tags" : "Thẻ"));

const blogListingSchema = computed(() => ({
  "@context": "https://schema.org",
  "@type": "CollectionPage",
  name: pageTitle.value,
  url: canonicalUrl.value,
  inLanguage: localeCode.value,
  mainEntity: {
    "@type": "ItemList",
    itemListElement: posts.value.map((post: any, index: number) => ({
      "@type": "ListItem",
      position: index + 1,
      url: post.url || `${siteUrl.value}/blog/${post.slug}`,
      name: post.name,
    })),
  },
}));

useJsonLd(blogListingSchema);
</script>

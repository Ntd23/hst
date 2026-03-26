<style>
.blog-hero {
  background: #e7e6dde0;
  border: 1px solid white;
  border-radius: 15px;
}
.prose {
  background: #dcf0fa;
  border: 1px solid white;
  border-radius: 15px;
}
</style>
<template>
  <main class="relative container w-full overflow-hidden">
    <div v-if="pending" class="mx-auto px-4 pb-20 pt-5 sm:px-6 lg:px-8">
      <div class="mb-10 grid grid-cols-1 gap-6 rounded-[15px] border border-white bg-white/70 p-5 shadow-md lg:grid-cols-12">
        <div class="space-y-4 lg:col-span-5">
          <div class="h-10 w-4/5 animate-pulse rounded-2xl bg-slate-200/80" />
          <div class="h-4 w-full animate-pulse rounded-xl bg-slate-200/70" />
          <div class="h-4 w-2/3 animate-pulse rounded-xl bg-slate-200/70" />
        </div>
        <div class="lg:col-span-7">
          <div class="aspect-video animate-pulse rounded-2xl bg-slate-200/80" />
        </div>
      </div>

      <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
        <div class="space-y-6 lg:col-span-8">
          <div class="rounded-3xl bg-white/70 p-5 shadow-md">
            <div
              v-for="index in 7"
              :key="`blog-detail-skeleton-${index}`"
              class="mb-4 h-4 animate-pulse rounded-xl bg-slate-200/70"
              :class="index === 1 ? 'w-11/12' : index === 7 ? 'w-4/6' : 'w-full'"
            />
          </div>
        </div>

        <aside class="space-y-8 lg:col-span-4">
          <div class="h-80 animate-pulse rounded-2xl bg-white/75 shadow-md" />
          <div class="h-48 animate-pulse rounded-2xl bg-white/75 shadow-md" />
        </aside>
      </div>
    </div>
    <div v-else>
      <CommonsAppBreadcrumb :title="breadcrumbTitle" :items="[{ label: post.name }]" />

      <div class="mx-auto px-4 pb-20 pt-5 sm:px-6 lg:px-8">
        <div
          class="blog-hero mb-10 grid grid-cols-1 items-center gap-6 p-5 shadow-md lg:col-span-12 lg:grid-cols-12"
        >
          <div class="flex flex-col justify-center lg:col-span-5">
            <h1 class="mb-6 text-3xl font-bold leading-tight text-slate-900 md:text-4xl">
              {{ post.name }}
            </h1>

            <div class="flex-wrap gap-4 border-b border-slate-200 pb-8 text-sm text-slate-500 md:gap-6">
              <div class="flex">
                <div
                  v-for="category in categories"
                  :key="category.id"
                  class="me-4 flex cursor-pointer items-center gap-1.5 rounded-full border border-blue-100 bg-blue-50 px-3 py-1 text-xs font-medium shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:bg-blue-100 hover:shadow-md"
                >
                  <span>{{ category.name }}</span>
                </div>
              </div>

              <div class="mt-4 flex items-center gap-1.5">
                <UIcon name="solar:calendar-broken" class="size-5" />
                <span>{{ post.formatted_published_at }}</span>
              </div>
            </div>
          </div>

          <div class="flex items-center lg:col-span-7">
            <div class="relative aspect-video w-full overflow-hidden rounded-2xl shadow-md">
              <NuxtImg :src="post.image" class="h-full w-full object-cover" />
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
          <div class="lg:col-span-8">
            <article class="glass-panel-heavy rounded-3xl">
              <div
                v-html="post.content"
                class="prose prose-lg max-w-none p-5 prose-slate shadow-md prose-headings:font-bold prose-img:rounded-xl prose-p:text-slate-600"
              ></div>
              <div class="mt-12 flex flex-col items-center justify-between gap-4 border-t border-slate-200 pt-8 md:flex-row">
                <div class="flex items-center gap-2">
                  <span class="text-sm font-semibold text-slate-700">{{ tagsLabel }}:</span>
                  <a
                    v-for="tag in tags"
                    :key="tag.id"
                    class="rounded-lg bg-slate-100 px-3 py-1 text-sm text-slate-600 transition-colors hover:bg-primary hover:text-white"
                    href="#"
                  >{{ tag.name }}</a>
                </div>
                <div class="flex items-center gap-3">
                  <span class="text-sm font-semibold text-slate-700">{{ shareLabel }}:</span>
                  <div class="flex gap-2">
                    <a
                      v-for="item in shareLinks"
                      :key="item.name"
                      :href="item.href"
                      :aria-label="item.label"
                      :title="item.label"
                      class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-500 transition-colors"
                      :class="item.className"
                      rel="noopener noreferrer"
                      target="_blank"
                    >
                      <span :class="item.textClass">{{ item.shortLabel }}</span>
                    </a>
                  </div>
                </div>
              </div>
              <div class="mt-8 flex items-center gap-4 rounded-2xl border border-blue-100 bg-blue-50/50 p-6">
                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-white p-1 shadow-sm">
                  <div class="flex h-full w-full items-center justify-center rounded-full bg-slate-900 text-center text-xs font-bold leading-none text-white">
                    SOTECH<br />GROUP
                  </div>
                </div>
                <div>
                  <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">{{ authorLabel }}</span>
                  <a
                    :href="siteUrl"
                    class="text-lg font-bold text-primary transition-colors hover:text-secondary"
                    rel="noopener noreferrer"
                    target="_blank"
                  >
                    HisoTech Group
                  </a>
                  <p class="mt-1 text-sm text-slate-600">
                    {{ authorDescription }}
                  </p>
                </div>
              </div>
            </article>
          </div>
          <aside class="space-y-8 lg:col-span-4">
            <div class="glass-panel rounded-2xl p-6 shadow-md">
              <h3 class="mb-6 border-b border-slate-100 pb-2 text-lg font-bold text-slate-900">
                {{ recentPostsLabel }}
              </h3>
              <div class="space-y-5">
                <NuxtLink
                  v-for="p in recentPosts"
                  :key="p.id"
                  class="group flex items-start gap-4"
                  :to="`/blog/${p.slug}`"
                >
                  <div class="relative h-20 w-20 shrink-0 overflow-hidden rounded-lg">
                    <img
                       :alt="p.name"
                      class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                      :src="p.image"
                    />
                  </div>
                  <div>
                    <h4 class="line-clamp-2 text-sm font-bold leading-snug text-slate-800 transition-colors group-hover:text-primary">
                      {{ p.name }}
                    </h4>
                    <div class="mt-2 flex items-center gap-1 text-xs text-slate-400">
                      <UIcon name="solar:calendar-broken" class="size-5" />
                      <span>{{ p.formatted_published_at }}</span>
                    </div>
                  </div>
                </NuxtLink>
              </div>
            </div>
            <div class="glass-panel rounded-2xl p-6 shadow-md">
              <h3 class="mb-6 border-b border-slate-100 pb-2 text-lg font-bold text-slate-900">
                {{ tagsLabel }}
              </h3>
              <div class="flex flex-wrap gap-2">
                <a
                  v-for="tag in tags"
                  :key="tag.id"
                  class="rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-600 transition-colors hover:bg-primary hover:text-white"
                  href="#"
                >{{ tag.name }}</a>
              </div>
            </div>
          </aside>
        </div>
      </div>
    </div>
  </main>
</template>

<script setup lang="ts">
import CommonsAppBreadcrumb from "~/components/commons/navigation/AppBreadcrumb.vue";

const props = defineProps<{
  slug: string;
}>();

const { translate, localeCode } = useI18nText();
const { pending, post, recentPosts, categories, tags } =
  await useBlogDetailPage(toRef(props, "slug"));
const {
  siteUrl,
  canonicalUrl,
  labels: shareSectionLabels,
  shareLinks,
  toAbsoluteUrl,
} = useBlogDetailShare({ post });

const breadcrumbTitle = computed(() =>
  translate("blogDetail.breadcrumb", localeCode.value === "en" ? "Blog Details" : "Chi tiết bài viết")
);
const tagsLabel = computed(() =>
  translate("blogDetail.tags", localeCode.value === "en" ? "Tags" : "Thẻ")
);
const shareLabel = computed(() => shareSectionLabels.value.share);
const authorLabel = computed(() =>
  translate("blogDetail.author", localeCode.value === "en" ? "Author" : "Tác giả")
);
const authorDescription = computed(() =>
  translate(
    "blogDetail.authorDescription",
    localeCode.value === "en"
      ? "Experts in comprehensive digital transformation consulting for businesses."
      : "Chuyên gia tư vấn giải pháp chuyển đổi số toàn diện cho doanh nghiệp."
  )
);
const recentPostsLabel = computed(() =>
  translate("blogDetail.recentPosts", localeCode.value === "en" ? "Recent Posts" : "Bài viết mới")
);
const articleDescription = computed(() => {
  const content = String(post.value?.content || "")
    .replace(/<[^>]*>/g, " ")
    .replace(/\s+/g, " ")
    .trim();

  return content || authorDescription.value;
});

const blogSchema = computed(() => {
  if (!post.value?.name) {
    return null;
  }

  const image = toAbsoluteUrl(post.value.image);

  return {
    "@context": "https://schema.org",
    "@type": "BlogPosting",
    headline: post.value.name,
    description: articleDescription.value,
    image,
    datePublished: post.value.published_at || undefined,
    dateModified: post.value.published_at || undefined,
    mainEntityOfPage: canonicalUrl.value,
    author: {
      "@type": "Organization",
      name: "HISOTECH Group",
    },
    publisher: {
      "@type": "Organization",
      name: "HISOTECH Group",
      logo: image
        ? {
            "@type": "ImageObject",
            url: image,
          }
        : undefined,
    },
    articleSection: categories.value?.map((item: any) => item.name) || undefined,
    keywords: tags.value?.map((item: any) => item.name)?.join(", ") || undefined,
  };
});

useJsonLd(blogSchema);
</script>

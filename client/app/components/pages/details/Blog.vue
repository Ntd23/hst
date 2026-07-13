<template>
  <main class="relative w-full overflow-hidden pb-20">
    <div v-if="pending" class="pb-20 pt-6">
      <UContainer>
        <div class="mb-8 h-10 w-72 animate-pulse rounded-2xl bg-white/75" />
        <div class="mb-12 h-4 w-96 animate-pulse rounded-xl bg-white/70" />

        <div class="mb-8 overflow-hidden rounded-[28px] border border-slate-100 bg-white shadow-sm">
          <div class="aspect-[16/8] animate-pulse bg-slate-200/80" />
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
          <div class="space-y-6 lg:col-span-8">
            <div class="rounded-[28px] border border-slate-100 bg-white p-8 shadow-sm">
              <div class="mb-5 h-12 w-11/12 animate-pulse rounded-2xl bg-slate-200/80" />
              <div class="mb-8 h-8 w-8/12 animate-pulse rounded-2xl bg-slate-200/70" />
              <div class="mb-8 flex gap-4">
                <div class="h-10 w-32 animate-pulse rounded-xl bg-slate-200/70" />
                <div class="h-10 w-24 animate-pulse rounded-xl bg-slate-200/70" />
                <div class="h-10 w-28 animate-pulse rounded-xl bg-slate-200/70" />
              </div>
              <div
                v-for="index in 8"
                :key="`blog-detail-skeleton-${index}`"
                class="mb-4 h-4 animate-pulse rounded-xl bg-slate-200/70"
                :class="index === 8 ? 'w-4/6' : 'w-full'"
              />
            </div>
          </div>

          <aside class="space-y-8 lg:col-span-4">
            <div class="h-80 animate-pulse rounded-2xl border border-slate-100 bg-white shadow-sm" />
            <div class="h-56 animate-pulse rounded-2xl border border-slate-100 bg-white shadow-sm" />
          </aside>
        </div>
      </UContainer>
    </div>

    <div v-else>
      <CommonsAppBreadcrumb
        :title="breadcrumbTitle"
        :items="[{ label: decodedPostName }]"
      />

      <UContainer class="pt-8">
        <div
          v-if="post.image"
          class="mb-10 overflow-hidden rounded-[32px] border border-white/70 bg-white shadow-[0_22px_60px_rgba(15,23,42,0.08)]"
        >
          <NuxtImg
            :src="post.image"
            :alt="decodedPostName"
            class="aspect-[16/8] w-full object-cover"
          />
        </div>

        <div class="grid grid-cols-1 gap-10 lg:grid-cols-12">
          <div class="lg:col-span-8">
            <article
              class="rounded-[32px] border border-white/70 bg-white px-7 py-8 shadow-[0_24px_70px_rgba(15,23,42,0.08)] sm:px-10 sm:py-10 lg:px-12 lg:py-12"
            >
              <header class="border-b border-slate-100/90 pb-8 lg:pb-10">
                <h1 class="max-w-5xl text-4xl font-bold leading-[1.08] tracking-tight text-slate-900 sm:text-5xl lg:text-[3.9rem]">
                  {{ decodedPostName }}
                </h1>

                <div class="mt-7 flex flex-wrap items-center gap-3 text-sm text-slate-500 lg:gap-4">
                  <div class="flex items-center gap-3 rounded-2xl border border-slate-200/80 bg-slate-50 px-4 py-3">
                    <div class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-full bg-slate-900 text-[10px] font-bold leading-none text-white">
                      HISO
                    </div>
                    <div class="flex items-center gap-1.5">
                      <span>{{ authorPrefix }}</span>
                      <a
                        :href="siteUrl"
                        class="font-semibold text-secondary"
                        rel="noopener noreferrer"
                        target="_blank"
                      >
                        HisoTech Group
                      </a>
                    </div>
                  </div>

                  <div
                    v-if="categories.length"
                    class="rounded-2xl border border-slate-200/80 bg-white px-4 py-3 font-medium text-slate-700"
                  >
                    {{ decodeHtml(categories[0].name) }}
                  </div>

                  <div class="flex items-center gap-2 rounded-2xl border border-slate-200/80 bg-white px-4 py-3">
                    <CommonsBotbleIcon icon="i-lucide-calendar" class="size-4 text-primary" />
                    <span>{{ post.formatted_published_at }}</span>
                  </div>
                </div>
              </header>

              <div
                v-html="post.content"
                class="blog-article prose prose-lg mt-10 max-w-none prose-slate prose-headings:font-bold prose-headings:text-slate-900 prose-img:rounded-[24px] prose-a:text-primary lg:mt-12"
              />

              <footer class="mt-12 border-t border-slate-100/90 pt-8">
                <div class="flex items-center gap-3 md:justify-end">
                  <span class="text-sm font-semibold text-slate-700">{{ shareLabel }}:</span>
                  <div class="flex items-center gap-3">
                    <div class="flex gap-2">
                      <a
                        v-for="item in shareLinks"
                        :key="item.name"
                        :href="item.href"
                        :aria-label="item.label"
                        :title="item.label"
                        class="flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500"
                        :class="item.className"
                        rel="noopener noreferrer"
                        target="_blank"
                      >
                        <span :class="item.textClass">{{ item.shortLabel }}</span>
                      </a>
                    </div>
                  </div>
                </div>
              </footer>
            </article>
          </div>

          <aside class="space-y-8 lg:col-span-4">
            <CommonsSidebarWidgets v-if="sidebarWidgets.length" :widgets="sidebarWidgets" />
          </aside>
        </div>
      </UContainer>
    </div>
  </main>
</template>

<script setup lang="ts">
import CommonsBotbleIcon from "~/components/commons/BotbleIcon.vue";
import CommonsAppBreadcrumb from "~/components/commons/navigation/AppBreadcrumb.vue";
import CommonsSidebarWidgets from "~/components/commons/renderers/SidebarWidgets.vue";

const props = defineProps<{
  slug: string;
}>();

const { translate, localeCode } = useI18nText();
const { decodeHtml } = useDecodeHtml();
const { pageData, pending, post, categories, tags, sidebarWidgets } =
  await useBlogDetailPage(toRef(props, "slug"));
const {
  siteUrl,
  canonicalUrl,
  labels: shareSectionLabels,
  shareLinks,
  toAbsoluteUrl,
} = useBlogDetailShare({ post });

const breadcrumbTitle = computed(() =>
  translate(
    "blogDetail.breadcrumb",
    localeCode.value === "en" ? "Blog Details" : "Chi tiết bài viết"
  )
);
const decodedPostName = computed(() => decodeHtml(post.value?.name));
const shareLabel = computed(() => shareSectionLabels.value.share);
const authorPrefix = computed(() =>
  translate("blogDetail.authorBy", localeCode.value === "en" ? "By" : "Bởi")
);

const blogSeo = computed(() => {
  const seo = pageData.value?.__seo;

  if (!seo) {
    return null;
  }

  return {
    title: seo.title || "",
    description: seo.description || "",
    image: seo.image || undefined,
    type: "article" as const,
    robots:
      typeof seo.index === "boolean"
        ? seo.index
          ? "index,follow"
          : "noindex,nofollow"
        : seo.robots || "",
    favicon: seo.favicon || seo.icon || undefined,
  };
});

const articleDescription = computed(() =>
  String(post.value?.content || "")
    .replace(/<[^>]*>/g, " ")
    .replace(/\s+/g, " ")
    .trim()
);

const blogSchema = computed(() => {
  if (!post.value?.name) {
    return null;
  }

  const image = toAbsoluteUrl(post.value.image);

  return {
    "@context": "https://schema.org",
    "@type": "BlogPosting",
    headline: decodedPostName.value,
    description: articleDescription.value || undefined,
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
    articleSection:
      categories.value?.map((item: any) => decodeHtml(item.name)) || undefined,
    keywords:
      tags.value?.map((item: any) => decodeHtml(item.name))?.join(", ") ||
      undefined,
  };
});

usePageSeo(blogSeo);
useJsonLd(blogSchema);
</script>

<style scoped>
.blog-article {
  color: rgb(51 65 85);
}

.blog-article :deep(p) {
  color: rgb(71 85 105);
  margin-bottom: 1.2rem;
  line-height: 1.9;
}

.blog-article :deep(h2),
.blog-article :deep(h3),
.blog-article :deep(h4) {
  margin-top: 2.25rem;
  margin-bottom: 1rem;
  letter-spacing: -0.02em;
}

.blog-article :deep(ul),
.blog-article :deep(ol) {
  padding-left: 1.25rem;
}

.blog-article :deep(li) {
  margin-bottom: 0.6rem;
}

.blog-article :deep(img) {
  border: 1px solid rgb(241 245 249);
  box-shadow: 0 18px 44px rgb(15 23 42 / 0.09);
}

.blog-article :deep(figure) {
  margin-top: 2rem;
  margin-bottom: 2rem;
}
</style>

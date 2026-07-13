<template>
  <main class="relative w-full bg-pastel-gradient">
    <div v-if="pending" class="pb-16 pt-8">
      <UContainer>
        <div class="mb-8 space-y-3">
          <div class="h-10 w-64 animate-pulse rounded-2xl bg-white/75" />
          <div class="h-4 w-48 animate-pulse rounded-xl bg-white/70" />
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
          <aside class="space-y-8 lg:col-span-3">
            <div class="h-72 animate-pulse rounded-2xl bg-white/80" />
            <div class="h-64 animate-pulse rounded-2xl bg-white/80" />
            <div class="h-64 animate-pulse rounded-2xl bg-white/80" />
          </aside>

          <div class="space-y-8 lg:col-span-9">
            <div class="h-80 animate-pulse rounded-3xl bg-white/80" />
            <div class="h-32 animate-pulse rounded-3xl bg-white/80" />
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
              <div
                v-for="index in 2"
                :key="`service-detail-skeleton-${index}`"
                class="h-56 animate-pulse rounded-3xl bg-white/80"
              />
            </div>
          </div>
        </div>
      </UContainer>
    </div>

    <template v-else-if="pageData">
      <section class="pb-16 pt-8">
        <UContainer>
          <div class="mb-8">
            <h1 class="mb-2 text-3xl font-bold text-slate-900 sm:text-4xl">{{ detailTitle }}</h1>
            <div class="flex flex-wrap items-center gap-2 text-sm text-slate-500">
              <NuxtLink to="/">{{ homeLabel }}</NuxtLink>
              <CommonsBotbleIcon icon="i-lucide-chevron-right" class="size-4 text-slate-300" />
              <span class="font-medium text-primary">{{ pageData.name }}</span>
            </div>
          </div>

          <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
            <aside class="space-y-8 lg:col-span-3">
              <CommonsSidebarWidgets v-if="sidebarWidgets.length" :widgets="sidebarWidgets" />
              <template v-else>
                <div v-if="sidebarServices.length" class="rounded-2xl border border-slate-100 bg-white p-2 shadow-sm">
                  <nav class="flex flex-col space-y-1">
                    <NuxtLink
                      v-for="service in sidebarServices"
                      :key="service.id || service.slug"
                      :to="service.slug ? `/services/${service.slug}` : '#'"
                      :class="[
                        'flex items-start justify-between gap-3 rounded-xl px-4 py-3 text-sm font-medium',
                        service.slug === props.slug
                          ? 'border-l-4 border-primary bg-primary/10 text-primary'
                          : 'text-slate-600 bg-white'
                      ]"
                    >
                      <span class="line-clamp-2">{{ service.name }}</span>
                      <CommonsBotbleIcon icon="i-heroicons-arrow-right-20-solid" class="size-4 shrink-0" />
                    </NuxtLink>
                  </nav>
                </div>

                <div v-if="handbookItems.length" class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                  <div class="mb-4 flex items-center gap-2 border-b border-slate-100 pb-3">
                    <CommonsBotbleIcon icon="i-lucide-book-open" class="size-5 text-primary" />
                    <h3 class="font-bold text-slate-800">{{ handbookLabel }}</h3>
                  </div>
                  <p class="mb-4 text-sm text-slate-500">
                    {{ handbookDescription }}
                  </p>
                  <div class="space-y-2">
                    <a
                      v-for="item in handbookItems"
                      :key="item.label"
                      class="flex items-center gap-3 rounded-lg bg-slate-50 p-3"
                      :href="item.url"
                    >
                      <img
                        v-if="item.icon_image"
                        :src="item.icon_image"
                        :alt="item.label || 'Icon'"
                        class="h-5 w-5 object-contain"
                      />
                      <CommonsBotbleIcon
                        v-else
                        :icon="item.icon"
                        :class="['size-5', item.color]"
                      />
                      <span class="text-sm font-medium text-slate-700">{{ item.label }}</span>
                    </a>
                  </div>
                </div>

                <div v-if="recentPosts.length" class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                  <div class="mb-4 flex items-center gap-2 border-b border-slate-100 pb-3">
                    <CommonsBotbleIcon icon="i-lucide-rss" class="size-5 text-primary" />
                    <h3 class="font-bold text-slate-800">{{ recentPostsLabel }}</h3>
                  </div>
                  <div class="space-y-4">
                    <NuxtLink
                      v-for="post in recentPosts"
                      :key="post.id"
                      :to="post.slug ? `/blog/${post.slug}` : '#'"
                      class="flex gap-3"
                    >
                      <div class="h-16 w-16 shrink-0 overflow-hidden rounded-lg bg-slate-200">
                        <img
                          :src="post.image"
                          :alt="post.name"
                          class="h-full w-full object-cover"
                        />
                      </div>
                      <div>
                        <h4 class="line-clamp-2 text-sm font-bold text-slate-800">
                          {{ post.name }}
                        </h4>
                        <div class="mt-1 flex items-center gap-1 text-xs text-slate-400">
                          <CommonsBotbleIcon icon="i-lucide-calendar" class="size-4" />
                          <span>{{ post.formatted_published_at }}</span>
                        </div>
                      </div>
                    </NuxtLink>
                  </div>
                </div>

                <div class="relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-6 text-slate-900 shadow-sm">
                  <div class="absolute right-0 top-0 translate-x-1/4 -translate-y-1/4 transform p-8 opacity-10">
                    <CommonsBotbleIcon icon="i-heroicons-chat-bubble-left-right-20-solid" class="size-24 text-slate-300" />
                  </div>
                  <h3 class="relative z-10 mb-2 text-xl font-bold">{{ supportTitle }}</h3>
                  <p class="relative z-10 mb-6 text-sm text-slate-500">{{ supportDescription }}</p>
                  <button
                    class="btn-shared-cta relative z-10 flex w-full items-center justify-center gap-2"
                    type="button"
                  >
                    <CommonsBotbleIcon icon="i-heroicons-phone-20-solid" class="size-5" />
                    +84 973 73 56 79
                  </button>
                </div>

                <WidgetContactForm :data="fallbackContactFormData" />
              </template>
            </aside>

            <div class="space-y-8 lg:col-span-9">
              <div v-if="pageData.image" class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-sm">
                <NuxtImg :src="pageData.image" :alt="pageData.name" class="h-72 w-full object-cover sm:h-80" />
              </div>

              <div>
                <h2 class="mb-3 text-2xl font-bold text-primary sm:text-3xl">
                  {{ pageData.name }}
                </h2>
                <p v-if="pageData.description" class="text-lg leading-relaxed text-slate-600">
                  {{ pageData.description }}
                </p>
              </div>

              <template v-if="Shortcodes.length > 0">
                <component
                  :is="sc.component"
                  v-for="(sc, index) in Shortcodes"
                  :key="index"
                  :data="sc.data"
                  v-bind="index >= 3 ? { 'hydrate-on-visible': true } : {}"
                />
              </template>

              <section v-if="cleanContent" class="prose prose-lg max-w-none prose-slate">
                <div v-html="cleanContent" />
              </section>
            </div>
          </div>
        </UContainer>
      </section>
    </template>

    <div v-else class="py-24 text-center">
      <h1 class="text-2xl font-bold text-gray-800">{{ notFoundTitle }}</h1>
      <p class="mt-2 text-gray-500">{{ notFoundDescription }}</p>
    </div>
  </main>
</template>

<script setup lang="ts">
import CommonsSidebarWidgets from "~/components/commons/renderers/SidebarWidgets.vue";
import WidgetContactForm from "~/components/widget/contact-form.vue";

const props = defineProps<{
  slug: string;
}>();

const { translate, localeCode } = useI18nText();
const { siteUrl, canonicalUrl } = useSeoContext();
const {
  pageData,
  pending,
  Shortcodes,
  sidebarServices,
  recentPosts,
  handbookItems,
  cleanContent,
  sidebarWidgets,
} = await useServiceDetailPage(toRef(props, "slug"));

const detailTitle = computed(() =>
  translate("serviceDetail.title", localeCode.value === "en" ? "Service Details" : "Chi tiết dịch vụ")
);
const homeLabel = computed(() => translate("nav.home", localeCode.value === "en" ? "Home" : "Trang chủ"));
const handbookLabel = computed(() =>
  translate("serviceDetail.handbook", localeCode.value === "en" ? "Handbook" : "Sổ tay")
);
const handbookDescription = computed(() =>
  translate(
    "serviceDetail.handbookDescription",
    localeCode.value === "en"
      ? "You can read more of our documents below."
      : "Bạn có thể đọc thêm các tài liệu dưới đây của chúng tôi"
  )
);
const recentPostsLabel = computed(() =>
  translate("serviceDetail.recentPosts", localeCode.value === "en" ? "Recent Posts" : "Bài đăng mới")
);
const supportTitle = computed(() =>
  translate(
    "serviceDetail.supportTitle",
    localeCode.value === "en" ? "If you need any support" : "Nếu bạn cần bất kì sự hỗ trợ nào"
  )
);
const supportDescription = computed(() =>
  translate(
    "serviceDetail.supportDescription",
    localeCode.value === "en" ? "call us right now" : "hãy gọi cho chúng tôi ngay bây giờ"
  )
);
const messageTitle = computed(() =>
  translate(
    "serviceDetail.messageTitle",
    localeCode.value === "en" ? "Send us a message" : "Gửi tin nhắn cho chúng tôi"
  )
);
const submitMessageLabel = computed(() =>
  translate("serviceDetail.submit", localeCode.value === "en" ? "Send message" : "Gửi tin nhắn")
);
const fallbackContactFormData = computed(() => ({
  title: messageTitle.value,
  button_label: submitMessageLabel.value,
  background_color: "#ffffff",
}));
const notFoundTitle = computed(() =>
  translate("serviceDetail.notFoundTitle", localeCode.value === "en" ? "Page Content Not Found" : "Không tìm thấy nội dung trang")
);
const notFoundDescription = computed(() =>
  translate(
    "serviceDetail.notFoundDescription",
    localeCode.value === "en"
      ? `The requested slug "${props.slug}" returned no data from the API.`
      : `Slug "${props.slug}" không trả về dữ liệu từ API.`
  )
);

const toAbsoluteUrl = (value?: string) => {
  if (!value) {
    return undefined;
  }

  if (/^https?:\/\//i.test(value)) {
    return value;
  }

  return `${siteUrl.value}${value.startsWith("/") ? value : `/${value}`}`;
};

const serviceSeo = computed(() => {
  const seo = pageData.value?.__seo;

  if (!seo) {
    return null;
  }

  return {
    title: seo.title || "",
    description: seo.description || "",
    image: seo.image || undefined,
    type: "website" as const,
    robots: typeof seo.index === "boolean"
      ? seo.index
        ? "index,follow"
        : "noindex,nofollow"
      : seo.robots || "",
    favicon: seo.favicon || seo.icon || undefined,
  };
});
const serviceSchema = computed(() => {
  if (!pageData.value?.name) {
    return null;
  }

  return {
    "@context": "https://schema.org",
    "@type": "Service",
    name: pageData.value.name,
    description: pageData.value.description || cleanContent.value?.replace(/<[^>]*>/g, " ").replace(/\s+/g, " ").trim() || undefined,
    url: canonicalUrl.value,
    image: toAbsoluteUrl(pageData.value.image),
    provider: {
      "@type": "Organization",
      name: "HISOTECH Group",
      url: siteUrl.value,
    },
    areaServed: localeCode.value === "en" ? "Global" : "Việt Nam",
    serviceType: pageData.value.name,
  };
});

usePageSeo(serviceSeo);
useJsonLd(serviceSchema);
</script>

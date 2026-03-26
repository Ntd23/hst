<template>
  <main class="relative container overflow-hidden py-12">
    <div class="relative z-10 mx-auto px-4">
      <div v-if="!dataWeb?.name" class="space-y-10">
        <div class="space-y-4">
          <div class="h-10 w-3/5 animate-pulse rounded-2xl bg-white/80" />
          <div class="h-5 w-2/3 animate-pulse rounded-xl bg-white/70" />
        </div>
        <div class="h-[400px] animate-pulse rounded-3xl bg-white/80 md:h-[600px]" />
        <div class="flex flex-wrap gap-4">
          <div
            v-for="index in 3"
            :key="`website-demo-cta-${index}`"
            class="h-12 w-40 animate-pulse rounded-xl bg-white/80"
          />
        </div>
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
          <div class="h-[28rem] animate-pulse rounded-[20px] bg-white/80 lg:col-span-8" />
          <div class="h-80 animate-pulse rounded-3xl bg-white/80 lg:col-span-4" />
        </div>
      </div>
      <template v-else>
      <div class="mb-12 text-center md:text-left">
        <h1 class="mb-4 text-3xl font-extrabold leading-tight text-slate-900 dark:text-white md:text-5xl">
          {{ dataWeb.name }}
        </h1>
        <div
          v-html="dataWeb.seo_description"
          class="max-w-2xl text-lg text-slate-600 dark:text-slate-400"
        ></div>
      </div>
      <div
        class="group relative mb-12 overflow-hidden rounded-3xl border border-white/50 shadow-2xl shadow-primary/20 dark:border-slate-700/50"
      >
        <div class="absolute inset-0 z-10 bg-gradient-to-t from-black/60 to-transparent opacity-60"></div>
        <NuxtImg
          :alt="heroImageAlt"
          class="h-[400px] w-full object-cover object-center transition duration-700 group-hover:scale-105 md:h-[600px]"
          :src="dataWeb.image"
        />
        <div class="absolute bottom-0 left-0 right-0 z-20 flex flex-col items-end justify-between gap-6 p-8 md:flex-row">
          <div class="glass-panel max-w-xl rounded-2xl border border-glass-border bg-glass-bg p-6 dark:border-glass-border-dark dark:bg-glass-bg-dark">
            <h3 class="mb-2 text-xl font-bold text-slate-900 dark:text-white">
              {{ userExperienceTitle }}
            </h3>
            <p class="text-sm text-slate-600 dark:text-slate-300">
              {{ userExperienceDescription }}
            </p>
          </div>
        </div>
      </div>
      <div class="mb-16 flex flex-wrap justify-center gap-4 md:justify-start">
        <NuxtLink
          class="flex translate-y-0 items-center space-x-2 rounded-xl bg-primary px-8 py-3 font-bold text-white shadow-lg shadow-primary/30 transition hover:-translate-y-1 hover:bg-sky-600"
          :to="dataWeb.url_client"
        >
          <span>{{ webDemoLabel }}</span>
          <UIcon name="solar:arrow-right-linear" class="size-5" />
        </NuxtLink>
        <NuxtLink
          class="flex translate-y-0 items-center space-x-2 rounded-xl border border-primary/20 bg-white px-8 py-3 font-bold text-primary shadow-sm transition hover:-translate-y-1 hover:border-primary hover:shadow-md dark:bg-slate-800"
          :to="dataWeb.url_admin"
        >
          <span>{{ adminDemoLabel }}</span>
          <UIcon name="solar:arrow-right-linear" class="size-5" />
        </NuxtLink>
        <NuxtLink
          class="flex translate-y-0 items-center space-x-2 rounded-xl bg-slate-900 px-8 py-3 font-bold text-white shadow-lg transition hover:-translate-y-1 hover:bg-slate-800 dark:bg-slate-700 dark:hover:bg-slate-600"
          :to="{ path: '/contact', query: { slug: route.params.detail } }"
        >
          <span>{{ contactNowLabel }}</span>
          <UIcon name="solar:arrow-right-linear" class="size-5" />
        </NuxtLink>
      </div>
      <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
        <div
          v-html="dataWeb.content"
          class="space-y-8 rounded-[20px] border border-white bg-[#dcf0fa] p-7 lg:col-span-8 [&>*]:mb-2 [&>p]:mb-2"
        ></div>
        <div class="space-y-6 lg:col-span-4">
          <div
            class="glass-panel sticky top-24 rounded-3xl border border-white/50 bg-white/80 p-6 shadow-glass dark:border-slate-700/50 dark:bg-slate-800/80"
          >
            <h3 class="mb-4 border-b border-gray-100 pb-2 text-lg font-bold text-slate-900 dark:border-gray-700 dark:text-white">
              {{ technicalInfoLabel }}
            </h3>
            <div class="space-y-4">
              <div>
                <div class="mb-1 text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">
                  {{ technologyLabel }}
                </div>
                <div class="flex flex-wrap gap-2">
                  <span class="rounded-lg bg-slate-100 px-3 py-1 text-sm font-medium text-slate-700 dark:bg-slate-700 dark:text-slate-300">Laravel</span>
                  <span class="rounded-lg bg-slate-100 px-3 py-1 text-sm font-medium text-slate-700 dark:bg-slate-700 dark:text-slate-300">VueJS</span>
                  <span class="rounded-lg bg-slate-100 px-3 py-1 text-sm font-medium text-slate-700 dark:bg-slate-700 dark:text-slate-300">MySQL</span>
                </div>
              </div>
              <div>
                <div class="mb-1 text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">
                  {{ lastUpdatedLabel }}
                </div>
                <div class="flex items-center font-medium text-slate-800 dark:text-slate-200">
                  <UIcon name="solar:calendar-broken" class="mx-2 size-5" />
                  {{ dataWeb.formatted_date }}
                </div>
              </div>
              <div>
                <div class="mb-1 text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">
                  {{ publishedByLabel }}
                </div>
                <div class="flex items-center gap-2">
                  <span class="flex h-6 w-6 items-center justify-center rounded-full bg-primary text-xs font-bold text-white">H</span>
                  <span class="font-medium text-slate-800 dark:text-slate-200">HISOTECH Group</span>
                </div>
              </div>
              <div class="mt-4 border-t border-gray-100 pt-4 dark:border-gray-700">
                <NuxtLink
                  :to="{ path: '/contact', query: { slug: route.params.detail } }"
                  class="w-full rounded-xl bg-slate-900 px-10 py-3 font-bold text-white transition hover:opacity-90 dark:bg-white dark:text-slate-900"
                >
                  {{ requestQuoteLabel }}
                </NuxtLink>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="mt-10">
        <h3 class="mb-6 border-b border-slate-100 pb-2 text-4xl font-bold text-slate-900">
          {{ moreDemosLabel }}
        </h3>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <CommonsProductItem
            v-for="web in pageData.demo_webs"
            :key="web.index"
            :title="web.name"
            :image="web.image"
            :slug="'/' + route.path.split('/')[1] + '/' + web.slug"
            :date="web.published_at"
          />
        </div>
      </div>
      </template>
    </div>
  </main>
</template>

<script setup lang="ts">
import CommonsProductItem from "~/components/commons/cards/ProductItem.vue";

const props = defineProps<{
  slug: string;
}>();

const { translate, localeCode } = useI18nText();
const { siteUrl, canonicalUrl } = useSeoContext();
const { route, pageData, dataWeb } = await useWebsiteDemoDetailPage(
  toRef(props, "slug")
);

const heroImageAlt = computed(() => `${dataWeb.value.name || "Demo"} preview`);
const userExperienceTitle = computed(() =>
  translate(
    "websiteDemoDetail.userExperienceTitle",
    localeCode.value === "en" ? "Smooth user experience" : "Trải nghiệm người dùng mượt mà"
  )
);
const userExperienceDescription = computed(() =>
  translate(
    "websiteDemoDetail.userExperienceDescription",
    localeCode.value === "en"
      ? "The interface is optimized for seamless exploration and conversion, ensuring maximum convenience."
      : "Giao diện được thiết kế tối ưu cho việc tìm kiếm và thao tác, đảm bảo sự tiện lợi tối đa."
  )
);
const webDemoLabel = computed(() =>
  translate("websiteDemoDetail.webDemo", localeCode.value === "en" ? "Web Demo" : "Web demo")
);
const adminDemoLabel = computed(() =>
  translate("websiteDemoDetail.adminDemo", localeCode.value === "en" ? "Admin Demo" : "Admin demo")
);
const contactNowLabel = computed(() =>
  translate("websiteDemoDetail.contactNow", localeCode.value === "en" ? "Contact now" : "Liên hệ ngay")
);
const technicalInfoLabel = computed(() =>
  translate("websiteDemoDetail.technicalInfo", localeCode.value === "en" ? "Technical information" : "Thông tin kỹ thuật")
);
const technologyLabel = computed(() =>
  translate("websiteDemoDetail.technology", localeCode.value === "en" ? "Technology" : "Công nghệ")
);
const lastUpdatedLabel = computed(() =>
  translate("websiteDemoDetail.lastUpdated", localeCode.value === "en" ? "Last updated" : "Cập nhật lần cuối")
);
const publishedByLabel = computed(() =>
  translate("websiteDemoDetail.publishedBy", localeCode.value === "en" ? "Published by" : "Phát hành bởi")
);
const requestQuoteLabel = computed(() =>
  translate("websiteDemoDetail.requestQuote", localeCode.value === "en" ? "Request a quote" : "Yêu cầu báo giá")
);
const moreDemosLabel = computed(() =>
  translate("websiteDemoDetail.moreDemos", localeCode.value === "en" ? "Web demos" : "Web demo")
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

const softwareSchema = computed(() => {
  if (!dataWeb.value?.name) {
    return null;
  }

  return {
    "@context": "https://schema.org",
    "@type": "SoftwareApplication",
    name: dataWeb.value.name,
    description:
      String(dataWeb.value.seo_description || "")
        .replace(/<[^>]*>/g, " ")
        .replace(/\s+/g, " ")
        .trim() || userExperienceDescription.value,
    applicationCategory: "BusinessApplication",
    operatingSystem: "Web",
    image: toAbsoluteUrl(dataWeb.value.image),
    url: canonicalUrl.value,
    publisher: {
      "@type": "Organization",
      name: "HISOTECH Group",
      url: siteUrl.value,
    },
    sameAs: [dataWeb.value.url_client, dataWeb.value.url_admin].filter(Boolean),
  };
});

useJsonLd(softwareSchema);
</script>

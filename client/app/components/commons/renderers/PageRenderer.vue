<template>
  <main class="relative w-full overflow-hidden">
    <CommonsAppBreadcrumb
      v-if="slug !== 'homepage' && !pending"
      :title="pageTitle"
      :items="[{ label: pageTitle }]"
    />

    <div v-if="pending" class="container px-4 pb-20 pt-8">
      <div class="mx-auto space-y-8">
        <template v-if="skeletonVariant === 'contact'">
          <div class="h-10 w-64 animate-pulse rounded-2xl bg-white/70" />
          <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
            <div class="glass-panel h-[24rem] animate-pulse rounded-3xl lg:col-span-5" />
            <div class="glass-panel h-[30rem] animate-pulse rounded-3xl lg:col-span-7" />
          </div>
        </template>

        <template v-else-if="skeletonVariant === 'blog'">
          <div class="h-10 w-56 animate-pulse rounded-2xl bg-white/70" />
          <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
            <div class="space-y-6 lg:col-span-8">
              <div
                v-for="index in 3"
                :key="`blog-skeleton-${index}`"
                class="overflow-hidden rounded-3xl border border-white/70 bg-white/70 shadow-sm"
              >
                <div class="aspect-[16/8] animate-pulse bg-slate-200/80" />
                <div class="space-y-4 p-6">
                  <div class="h-7 w-3/4 animate-pulse rounded-xl bg-slate-200/80" />
                  <div class="h-4 w-full animate-pulse rounded-xl bg-slate-200/70" />
                  <div class="h-4 w-5/6 animate-pulse rounded-xl bg-slate-200/70" />
                </div>
              </div>
            </div>
            <div class="space-y-6 lg:col-span-4">
              <div class="h-14 animate-pulse rounded-2xl bg-white/80" />
              <div class="h-64 animate-pulse rounded-3xl bg-white/80" />
              <div class="h-64 animate-pulse rounded-3xl bg-white/80" />
            </div>
          </div>
        </template>

        <template v-else-if="skeletonVariant === 'services'">
          <div class="h-10 w-60 animate-pulse rounded-2xl bg-white/70" />
          <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
            <div class="space-y-6 lg:col-span-3">
              <div class="h-72 animate-pulse rounded-3xl bg-white/80" />
              <div class="h-56 animate-pulse rounded-3xl bg-white/80" />
            </div>
            <div class="space-y-8 lg:col-span-9">
              <div class="h-80 animate-pulse rounded-[2rem] bg-white/80" />
              <div class="h-36 animate-pulse rounded-3xl bg-white/80" />
              <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div
                  v-for="index in 2"
                  :key="`service-skeleton-${index}`"
                  class="h-56 animate-pulse rounded-3xl bg-white/80"
                />
              </div>
            </div>
          </div>
        </template>

        <template v-else>
          <div class="h-10 w-56 animate-pulse rounded-2xl bg-white/70" />
          <div class="space-y-8">
            <div class="h-80 animate-pulse rounded-[2rem] bg-white/80" />
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
              <div
                v-for="index in 6"
                :key="`default-skeleton-${index}`"
                class="h-52 animate-pulse rounded-3xl bg-white/80"
              />
            </div>
          </div>
        </template>
      </div>
    </div>
    <template v-else-if="Shortcodes.length > 0">
      <component
        v-for="(Shortcode, index) in Shortcodes"
        :key="index"
        :is="Shortcode.component"
        :data="Shortcode.data"
        v-bind="index >= 3 ? { 'hydrate-on-visible': true } : {}"
      />
    </template>
    <div v-else class="py-24 text-center">
      <h1 class="text-2xl font-bold text-gray-800">{{ notFoundTitle }}</h1>
      <p class="mt-2 text-gray-500">{{ notFoundDescription }}</p>
    </div>
  </main>
</template>

<script setup lang="ts">
import CommonsAppBreadcrumb from "~/components/commons/navigation/AppBreadcrumb.vue";

const props = defineProps<{
  slug: string;
}>();

const { translate, localeCode } = useI18nText();
const { pending, Shortcodes, pageTitle } = await usePageRenderer(
  toRef(props, "slug")
);

const notFoundTitle = computed(() =>
  translate(
    "pageRenderer.notFoundTitle",
    localeCode.value === "en" ? "Page Content Not Found" : "Không tìm thấy nội dung trang"
  )
);
const notFoundDescription = computed(() =>
  translate(
    "pageRenderer.notFoundDescription",
    localeCode.value === "en"
      ? `The requested slug "${props.slug}" returned no sections from the API.`
      : `Slug "${props.slug}" không trả về section nào từ API.`
  )
);
const skeletonVariant = computed(() => {
  const slug = props.slug.toLowerCase();

  if (slug.includes("contact") || slug.includes("lien-he")) {
    return "contact";
  }

  if (slug.includes("blog") || slug.includes("news") || slug.includes("post")) {
    return "blog";
  }

  if (slug.includes("service") || slug.includes("dich-vu")) {
    return "services";
  }

  return "default";
});
</script>

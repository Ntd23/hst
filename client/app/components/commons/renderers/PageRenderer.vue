<template>
  <main class="relative w-full overflow-hidden">
    <CommonsAppBreadcrumb
      v-if="slug !== 'homepage' && !pending"
      :title="pageTitle"
      :items="[{ label: pageTitle }]"
    />

    <div v-if="(pending || !contentReady) && skeletonVariant === 'homepage'">
      <!-- Hero slider skeleton — full viewport like the actual slider -->
      <header class="relative h-screen min-h-[560px] max-h-[960px] overflow-hidden bg-slate-950">
        <div class="absolute inset-0 animate-pulse bg-gradient-to-br from-slate-300/70 via-slate-200/45 to-slate-400/65" />
        <div class="relative z-10 flex h-full items-center px-5 py-16 sm:px-8 sm:py-20">
          <div class="mx-auto flex h-full w-full max-w-6xl items-center">
            <div class="grid w-full gap-10 lg:grid-cols-[minmax(0,1.05fr)_minmax(320px,0.8fr)] lg:items-center">
              <div class="flex flex-col justify-center">
                <div class="mb-6 h-4 w-40 rounded-full bg-white/30 sm:w-52" />
                <div class="space-y-4">
                  <div class="h-14 w-full max-w-3xl rounded-[1.75rem] bg-white/55 sm:h-16 lg:h-20" />
                  <div class="h-14 w-[92%] max-w-[42rem] rounded-[1.75rem] bg-white/50 sm:h-16 lg:h-20" />
                </div>
                <div class="mt-8 space-y-3">
                  <div class="h-4 w-full max-w-2xl rounded-full bg-white/28" />
                  <div class="h-4 w-[88%] max-w-xl rounded-full bg-white/22" />
                  <div class="h-4 w-[72%] max-w-lg rounded-full bg-white/18" />
                </div>
                <div class="mt-10 flex items-center gap-4">
                  <div class="h-14 w-52 rounded-full bg-white/40" />
                  <div class="hidden h-14 w-14 rounded-full bg-white/22 sm:block" />
                </div>
              </div>
              <div class="hidden lg:flex lg:justify-end">
                <div class="w-full max-w-md space-y-5">
                  <div class="h-72 rounded-[2rem] bg-white/18 backdrop-blur-sm xl:h-80" />
                  <div class="grid grid-cols-2 gap-4">
                    <div class="h-24 rounded-[1.5rem] bg-white/16" />
                    <div class="h-24 rounded-[1.5rem] bg-white/12" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="absolute bottom-8 left-1/2 flex -translate-x-1/2 items-center gap-3 sm:bottom-10">
            <div class="h-1.5 w-16 rounded-full bg-white/45" />
            <div class="h-1.5 w-8 rounded-full bg-white/25" />
            <div class="h-1.5 w-8 rounded-full bg-white/25" />
          </div>
        </div>
      </header>

      <!-- Below-the-fold section skeletons -->
      <div class="container px-4 pb-20 pt-12">
        <div class="mx-auto space-y-8">
          <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
            <div
              v-for="index in 3"
              :key="`homepage-skeleton-${index}`"
              class="h-64 animate-pulse rounded-[1.75rem] bg-slate-200/75 sm:h-72"
            />
          </div>
        </div>
      </div>
    </div>

    <div v-else-if="pending || !contentReady" class="container px-4 pb-20 pt-8">
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
    <Transition name="page-content-fade" mode="out-in">
      <div
        v-if="contentReady"
        key="page-content"
        class="page-content-shell"
      >
        <component
          v-for="(Shortcode, index) in Shortcodes"
          :key="index"
          :is="Shortcode.component"
          :data="Shortcode.data"
        />
      </div>
    </Transition>
    <div
      v-if="!pending && Shortcodes.length === 0"
      class="py-24 text-center"
    >
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
const { pageReady } = useAppBoot();
const { pending, Shortcodes, pageTitle } = await usePageRenderer(
  toRef(props, "slug")
);

watchEffect(() => {
  pageReady.value = !pending.value;
});

const contentReady = computed(
  () => !pending.value && Shortcodes.value.length > 0
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

  if (slug === "homepage" || slug === "/") {
    return "homepage";
  }

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

<style scoped>
.page-content-shell {
  min-height: 40vh;
}

.page-content-fade-enter-active,
.page-content-fade-leave-active {
  transition: opacity 0.25s ease;
}

.page-content-fade-enter-from,
.page-content-fade-leave-to {
  opacity: 0;
}
</style>

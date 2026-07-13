<template>
  <div class="container grid grid-cols-12 gap-10">
    <div class="relative col-span-12 lg:col-span-8">
      <NuxtImg
        :src="posts.post_1.image"
        :alt="posts.post_1.name || 'Featured blog post image'"
        class="h-[450px] w-full object-cover"
      />

      <div class="absolute right-0 top-[400px] w-[85%] bg-[#e9e4d6] p-8 shadow-lg">
        <h2 class="text-3xl font-extrabold leading-tight">
          {{ posts.post_1.name }}
        </h2>

        <p class="mt-4 line-clamp-2 text-gray-700">
          {{ preview }}
        </p>

        <div class="mt-3 flex items-center justify-between gap-3 text-xs font-semibold uppercase">
          <NuxtLink
            :to="`/blog/${posts.post_1.slug}`"
            class="flex rounded bg-blue-600 px-6 py-2 text-sm font-semibold uppercase text-white transition hover:bg-blue-700"
          >
            <span>{{ readMoreLabel }}</span>
            <CommonsBotbleIcon icon="i-lucide-arrow-right" class="size-5" />
          </NuxtLink>

          <div class="flex items-center gap-2 text-sm text-gray-500">
            <CommonsBotbleIcon icon="i-lucide-calendar" class="size-5" />
            <span>{{ formattedPrimaryDate }}</span>
          </div>
        </div>
      </div>
    </div>

    <div class="col-span-12 space-y-8 lg:col-span-4">
      <div v-for="item in postsRight" :key="item.id">
        <CommonsBlogItem
          :title="item.name"
          :image="item.image"
          :slug="item.slug"
          :date="item.created_at"
        />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import CommonsBotbleIcon from "~/components/commons/BotbleIcon.vue";
import CommonsBlogItem from "~/components/commons/cards/BlogItem.vue";

const props = defineProps({
  data: {
    type: Object,
    default: () => ({}),
  },
});

const { localeCode, translate } = useI18nText();
const { formatDate } = useCommonCardText();
const { posts, postsRight, preview } =
  useBlogPostFeaturedShortcode(toRef(props, "data"));

const readMoreLabel = computed(() =>
  translate("news.readMore", localeCode.value === "en" ? "Read more" : "Xem thêm")
);
const formattedPrimaryDate = computed(() => formatDate(posts.value?.post_1?.created_at));
</script>

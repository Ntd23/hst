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
  <main class="relative overflow-hidden w-full container">
    <div v-if="pending" class="flex min-h-[60vh] items-center justify-center">
      <UIcon
        name="i-lucide-loader-2"
        class="size-8 animate-spin text-primary"
      />
    </div>
    <div v-else class="">
      <!-- Breadcrumb -->
      <CommonsAppBreadcrumb
        title="Chi tiết bài viết"
        :items="[
          { label: post.name }
        ]"
      />

      <div class="pt-5 pb-20 px-4 sm:px-6 lg:px-8 mx-auto">
        <div
          class="lg:col-span-12 grid grid-cols-1 lg:grid-cols-12 gap-6 items-center p-5 mb-10 shadow-md blog-hero"
        >
          <!-- LEFT -->
          <div class="lg:col-span-5 flex flex-col justify-center">
            <h1
              class="text-3xl md:text-4xl font-bold text-slate-900 mb-6 leading-tight"
            >
              {{ post.name }}
            </h1>

            <div
              class="flex-wrap items-center gap-4 md:gap-6 text-sm text-slate-500 pb-8 border-b border-slate-200"
            >
              <div class="flex">
                <div
                  v-for="category in categories"
                  :key="category.id"
                  class="flex me-4 items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-primary border border-blue-100 shadow-sm text-xs font-medium cursor-pointer transition-all duration-200 hover:bg-blue-100 hover:shadow-md hover:-translate-y-0.5"
                >
                  <span>{{ category.name }}</span>
                </div>
              </div>

              <div class="flex items-center gap-1.5 mt-4">
                <UIcon name="solar:calendar-broken" class="size-5" />
                <span>{{ post.published_at }}</span>
              </div>
            </div>
          </div>
          <!-- RIGHT -->
          <div class="lg:col-span-7 flex items-center">
            <div
              class="relative w-full aspect-video rounded-2xl overflow-hidden shadow-md"
            >
              <NuxtImg :src="post.image" class="w-full h-full object-cover" />
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
          <div class="lg:col-span-8">
            <article class="glass-panel-heavy rounded-3xl">
              <div
                v-html="post.content"
                class="prose p-5 prose-lg prose-slate max-w-none prose-headings:font-bold shadow-md prose-p:text-slate-600 prose-img:rounded-xl"
              ></div>
              <div
                class="mt-12 pt-8 border-t border-slate-200 flex flex-col md:flex-row justify-between items-center gap-4"
              >
                <div class="flex items-center gap-2">
                  <span class="text-sm font-semibold text-slate-700">Thẻ:</span>
                  <a
                    v-for="tag in tags"
                    :key="tag.id"
                    class="px-3 py-1 rounded-lg bg-slate-100 text-slate-600 hover:bg-primary hover:text-white transition-colors text-sm"
                    href="#"
                  >{{ tag.name }}</a>
                </div>
                <div class="flex items-center gap-3">
                  <span class="text-sm font-semibold text-slate-700">Chia sẻ:</span>
                  <div class="flex gap-2">
                    <button
                      class="w-8 h-8 rounded-full bg-slate-100 hover:bg-[#1877F2] hover:text-white flex items-center justify-center transition-colors text-slate-500"
                    >
                      <i class="fab fa-facebook-f text-sm">f</i>
                    </button>
                    <button
                      class="w-8 h-8 rounded-full bg-slate-100 hover:bg-black hover:text-white flex items-center justify-center transition-colors text-slate-500"
                    >
                      <span class="text-sm font-bold">X</span>
                    </button>
                    <button
                      class="w-8 h-8 rounded-full bg-slate-100 hover:bg-[#E60023] hover:text-white flex items-center justify-center transition-colors text-slate-500"
                    >
                      <span class="text-sm font-bold">P</span>
                    </button>
                    <button
                      class="w-8 h-8 rounded-full bg-slate-100 hover:bg-[#0077B5] hover:text-white flex items-center justify-center transition-colors text-slate-500"
                    >
                      <span class="text-xs font-bold">in</span>
                    </button>
                  </div>
                </div>
              </div>
              <div
                class="mt-8 p-6 rounded-2xl bg-blue-50/50 border border-blue-100 flex items-center gap-4"
              >
                <div
                  class="w-16 h-16 rounded-full bg-white shadow-sm flex items-center justify-center p-1 shrink-0"
                >
                  <div
                    class="w-full h-full rounded-full bg-slate-900 flex items-center justify-center text-white text-xs font-bold text-center leading-none"
                  >
                    SOTECH<br />GROUP
                  </div>
                </div>
                <div>
                  <span
                    class="text-xs font-semibold text-slate-500 uppercase tracking-wider"
                  >Tác giả</span>
                  <h4 class="text-lg font-bold text-primary">HisoTech Group</h4>
                  <p class="text-sm text-slate-600 mt-1">
                    Chuyên gia tư vấn giải pháp chuyển đổi số toàn diện cho
                    doanh nghiệp.
                  </p>
                </div>
              </div>
            </article>
          </div>
          <aside class="lg:col-span-4 space-y-8">
            <div class="glass-panel rounded-2xl shadow-md p-6">
              <h3
                class="text-lg font-bold text-slate-900 mb-6 pb-2 border-b border-slate-100"
              >
                Bài viết mới
              </h3>
              <div class="space-y-5">
                <NuxtLink
                  v-for="p in post_new"
                  :key="p.id"
                  class="group flex gap-4 items-start"
                  :to="`/blog/${p.slug}`"
                >
                  <div
                    class="w-20 h-20 rounded-lg overflow-hidden shrink-0 relative"
                  >
                    <img
                      alt="Post"
                      class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                      :src="p.image"
                    />
                  </div>
                  <div>
                    <h4
                      class="text-sm font-bold text-slate-800 group-hover:text-primary transition-colors line-clamp-2 leading-snug"
                    >
                      {{ p.name }}
                    </h4>
                    <div
                      class="flex items-center gap-1 mt-2 text-xs text-slate-400"
                    >
                      <UIcon name="solar:calendar-broken" class="size-5" />
                      <span>{{ p.published_at }}</span>
                    </div>
                  </div>
                </NuxtLink>
              </div>
            </div>
            <div class="glass-panel rounded-2xl shadow-md p-6">
              <h3
                class="text-lg font-bold text-slate-900 mb-6 pb-2 border-b border-slate-100"
              >
                Tags
              </h3>
              <div class="flex flex-wrap gap-2">
                <a
                  v-for="tag in tags"
                  :key="tag.id"
                  class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 text-xs font-medium hover:bg-primary hover:text-white transition-colors"
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
import { ref } from "vue";
import { useMappedShortcodes } from "~/composables/useMappedShortcodes";

const props = defineProps<{
  slug: string;
}>();

const { Shortcodes, mapSectionsToShortcodes } = useMappedShortcodes();
const { data: pageData, pending } = await usePageDetail<any>(props.slug);
console.log(pageData.value);

const post = ref({
  name: pageData.value?.name,
  image: pageData.value?.image,
  content: pageData.value?.content,
  published_at: pageData.value?.published_at,
});
const post_new = pageData.value?.posts;
const categories = pageData.value?.categories;
const tags = pageData.value?.tags;

if (pageData.value?.sections) {
  mapSectionsToShortcodes(pageData.value.sections);
}
</script>

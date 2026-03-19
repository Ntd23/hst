<template>
  <main class="py-12 relative overflow-hidden">
    <div
      class="absolute top-0 right-0 -mr-40 -mt-40 w-96 h-96 rounded-full bg-primary/10 blur-3xl pointer-events-none"
    ></div>
    <div
      class="absolute bottom-0 left-0 -ml-40 -mb-40 w-96 h-96 rounded-full bg-blue-500/10 blur-3xl pointer-events-none"
    ></div>
    <div class="container mx-auto px-4 relative z-10">
      <div class="mb-12 text-center md:text-left">
        <h1
          class="text-3xl md:text-5xl font-extrabold text-slate-900 dark:text-white mb-4 leading-tight"
        >
          {{ dataWeb.name }}
        </h1>
        <div
          v-html="dataWeb.seo_description"
          class="text-lg text-slate-600 dark:text-slate-400 max-w-2xl"
        ></div>
      </div>
      <div
        class="mb-12 rounded-3xl overflow-hidden shadow-2xl shadow-primary/20 border border-white/50 dark:border-slate-700/50 relative group"
      >
        <div
          class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent z-10 opacity-60"
        ></div>
        <NuxtImg
          alt="Grocery Platform Dashboard Preview"
          class="w-full h-[400px] md:h-[600px] object-cover object-center transform transition duration-700 group-hover:scale-105"
          :src="dataWeb.image"
        />
        <div
          class="absolute bottom-0 left-0 right-0 p-8 z-20 flex flex-col md:flex-row items-end justify-between gap-6"
        >
          <div
            class="glass-panel bg-glass-bg dark:bg-glass-bg-dark border border-glass-border dark:border-glass-border-dark p-6 rounded-2xl max-w-xl"
          >
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">
              Trải nghiệm người dùng mượt mà
            </h3>
            <p class="text-slate-600 dark:text-slate-300 text-sm">
              Giao diện được thiết kế tối ưu cho việc tìm kiếm và đặt hàng thực
              phẩm tươi sống, đảm bảo sự tiện lợi tối đa.
            </p>
          </div>
        </div>
      </div>
      <div class="flex flex-wrap gap-4 mb-16 justify-center md:justify-start">
        <NuxtLink
          class="flex items-center space-x-2 bg-primary hover:bg-sky-600 text-white px-8 py-3 rounded-xl font-bold transition shadow-lg shadow-primary/30 transform hover:-translate-y-1"
          :to="dataWeb.url_client"
        >
          <span>WEB DEMO</span>
          <UIcon name="solar:arrow-right-linear" class="size-5" />
        </NuxtLink>
        <NuxtLink
          class="flex items-center space-x-2 bg-white dark:bg-slate-800 text-primary border border-primary/20 hover:border-primary px-8 py-3 rounded-xl font-bold transition shadow-sm hover:shadow-md transform hover:-translate-y-1"
          :to="dataWeb.url_admin"
        >
          <span>ADMIN DEMO</span>
          <UIcon name="solar:arrow-right-linear" class="size-5" />
        </NuxtLink>
        <NuxtLink
          class="flex items-center space-x-2 bg-slate-900 dark:bg-slate-700 text-white hover:bg-slate-800 dark:hover:bg-slate-600 px-8 py-3 rounded-xl font-bold transition shadow-lg transform hover:-translate-y-1"
          href="#"
        >
          <span>LIÊN HỆ NGAY</span>
          <UIcon name="solar:arrow-right-linear" class="size-5" />
        </NuxtLink>
      </div>
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div
          v-html="dataWeb.content"
          class="lg:col-span-8 space-y-8 [&>*]:mb-2 [&>p]:mb-2"
        ></div>
        <div class="lg:col-span-4 space-y-6">
          <div
            class="glass-panel bg-white/80 dark:bg-slate-800/80 border border-white/50 dark:border-slate-700/50 p-6 rounded-3xl shadow-glass sticky top-24"
          >
            <h3
              class="font-bold text-lg text-slate-900 dark:text-white mb-4 border-b border-gray-100 dark:border-gray-700 pb-2"
            >
              Thông tin kỹ thuật
            </h3>
            <div class="space-y-4">
              <div>
                <div
                  class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1"
                >
                  Công nghệ
                </div>
                <div class="flex flex-wrap gap-2">
                  <span
                    class="px-3 py-1 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg text-sm font-medium"
                    >Laravel</span
                  >
                  <span
                    class="px-3 py-1 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg text-sm font-medium"
                    >VueJS</span
                  >
                  <span
                    class="px-3 py-1 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg text-sm font-medium"
                    >MySQL</span
                  >
                </div>
              </div>
              <div>
                <div
                  class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1"
                >
                  Cập nhật lần cuối
                </div>
                <div
                  class="text-slate-800 dark:text-slate-200 font-medium flex items-center"
                >
                  <UIcon name="solar:calendar-broken" class="size-5 mx-2" />
                  {{ dataWeb.date }}
                </div>
              </div>
              <div>
                <div
                  class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1"
                >
                  Phát hành bởi
                </div>
                <div class="flex items-center gap-2">
                  <span
                    class="w-6 h-6 bg-primary rounded-full flex items-center justify-center text-white text-xs font-bold"
                    >H</span
                  >
                  <span class="text-slate-800 dark:text-slate-200 font-medium"
                    >HISOTECH Group</span
                  >
                </div>
              </div>
              <div
                class="pt-4 mt-4 border-t border-gray-100 dark:border-gray-700"
              >
                <button
                  class="w-full bg-slate-900 dark:bg-white dark:text-slate-900 text-white py-3 rounded-xl font-bold hover:opacity-90 transition"
                >
                  Yêu cầu báo giá
                </button>
              </div>
            </div>
          </div>
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

const dataWeb = ref({
  name: pageData.value?.name,
  content: pageData.value?.content,
  image: pageData.value?.img_feautrer,
  url_admin: pageData.value?.url_admin,
  url_client: pageData.value?.url_client,
  seo_description: pageData.value?.seo_description,
  date: pageData.value?.date,
});
console.log(dataWeb);

if (pageData.value?.sections) {
  mapSectionsToShortcodes(pageData.value.sections);
}
</script>
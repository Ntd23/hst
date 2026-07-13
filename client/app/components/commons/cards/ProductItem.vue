<style>
.glass-card {
  background: rgba(255, 255, 255, 0.949);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 1px solid rgba(255, 255, 255, 0.5);
  box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
}
.dark .glass-card {
  background: rgba(30, 41, 59, 0.7);
  border: 1px solid rgba(255, 255, 255, 0.05);
  box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
}
.bg-avata {
  background: #1e3b8a;
}
</style>
<template>
  <div
    class="glass-card group overflow-hidden rounded-2xl transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-blue-500/10"
  >
    <div class="relative h-56 overflow-hidden shadow-[0_10px_20px_rgba(0,0,0,0.07)]">
      <div class="group relative h-56 overflow-hidden">
        <NuxtImg
          :src="props.image"
          :alt="props.title || 'Product image'"
          class="absolute left-0 top-0 w-full transition-all duration-[5500ms] ease-linear group-hover:top-[-800%]"
        />
      </div>
      <div
        class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"
      ></div>
    </div>
    <div class="p-6">
      <NuxtLink
        :to="props.slug"
        class="mb-2 line-clamp-2 text-xl font-bold text-hisotech-blue hover:underline dark:text-blue-300"
      >
        {{ props.title }}
      </NuxtLink>
      <div class="mb-6 flex items-center space-x-2">
        <div
          class="bg-avata flex h-8 w-8 items-center justify-center rounded-full text-xs font-bold text-white"
        >
          HT
        </div>
        <span class="text-sm text-slate-600 dark:text-slate-400"
          >{{ byLabel }}
          <span class="font-semibold text-slate-800 dark:text-slate-200">HisoTech</span></span
        >
      </div>
      <div class="flex items-center justify-between border-t border-slate-200 pt-4 dark:border-slate-700">
        <NuxtLink
          :to="props.slug"
          class="flex items-center space-x-1 rounded-lg border border-transparent bg-blue-50 px-3 py-1.5 font-medium text-primary transition-all hover:border-blue-300 hover:bg-blue-100 hover:text-primary-dark"
        >
          <span
            class="cursor-pointer text-xs uppercase tracking-wide transition-colors duration-300 hover:text-blue-600"
          >
            {{ detailLabel }}
          </span>
          <span class="material-symbols-outlined text-sm">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="18"
              height="18"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <path d="M5 12h14" />
              <path d="M13 5l7 7-7 7" />
            </svg>
          </span>
        </NuxtLink>
        <div v-if="formattedDate" class="flex items-center text-xs text-slate-400">
          <CommonsBotbleIcon icon="i-lucide-calendar" class="size-5" />
          {{ formattedDate }}
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import CommonsBotbleIcon from "~/components/commons/BotbleIcon.vue";

const props = defineProps<{
  title: string;
  image: string;
  slug: string;
  date?: string;
}>();
const { byLabel, detailLabel, formatDate } = useCommonCardText();
const formattedDate = computed(() => formatDate(props.date));
</script>


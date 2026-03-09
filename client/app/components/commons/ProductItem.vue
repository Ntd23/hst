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
    class="glass-card rounded-2xl overflow-hidden group hover:shadow-xl hover:shadow-blue-500/10 transition-all duration-300 transform hover:-translate-y-1"
  >
    <div class="relative overflow-hidden h-56">
      <NuxtImg
        alt="Real Estate Website Demo"
        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
        :src="image"
      />
      <div
        class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"
      ></div>
    </div>
    <div class="p-6">
      <h3
        class="text-xl font-bold text-hisotech-blue dark:text-blue-300 mb-2 line-clamp-2"
      >
        {{ title }}
      </h3>
      <div class="flex items-center space-x-2 mb-6">
        <div
          class="w-8 h-8 rounded-full bg-avata flex items-center justify-center text-white text-xs font-bold"
        >
          HT
        </div>
        <span class="text-sm text-slate-600 dark:text-slate-400"
          >Bởi
          <span class="font-semibold text-slate-800 dark:text-slate-200"
            >HisoTech</span
          ></span
        >
      </div>
      <div
        class="flex justify-between items-center border-t border-slate-200 dark:border-slate-700 pt-4"
      >
        <a
          class="flex items-center space-x-1 text-primary hover:text-primary-dark font-medium transition-colors bg-blue-50 dark:bg-blue-900/30 px-3 py-1.5 rounded-lg"
          href="#"
        >
          <span class="text-xs uppercase tracking-wide">Chi tiết</span>
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
        </a>
        <div class="flex items-center text-xs text-slate-400">
          <span class="material-symbols-outlined text-sm mr-1"
            >calendar_today</span
          >
          17-04-2025
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  title: string;
  image: string;
}>();
</script>
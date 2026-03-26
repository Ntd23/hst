<template>
  <footer class="mt-10 bg-[#0b1120] py-12 text-slate-300 sm:py-16">
    <UContainer>
      <div
        class="mb-10 grid grid-cols-1 gap-8 pb-10 md:grid-cols-2 lg:grid-cols-[minmax(250px,1.2fr)_minmax(200px,1fr)_minmax(200px,1fr)_minmax(320px,1.3fr)] lg:gap-10 sm:mb-12 sm:pb-12"
      >
        <div class="space-y-6">
          <h3 class="text-2xl font-black tracking-tighter text-white">
            HISOTECH
          </h3>
          <p class="text-sm leading-relaxed text-slate-400">
            {{ $t("footer.description") }}
          </p>
          <div class="flex space-x-4">
            <ULink
              v-for="social in socials"
              :key="social.label"
              :to="social.to"
              class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-800 transition-colors hover:bg-primary hover:text-white"
            >
              <span class="text-xs font-bold">{{ social.label }}</span>
            </ULink>
          </div>
        </div>

        <div>
          <h4 class="mb-6 font-bold text-white">{{ $t("footer.services") }}</h4>
          <ul class="space-y-3 text-sm">
            <li v-for="link in serviceLinks" :key="link">
              <ULink
                class="block whitespace-nowrap transition-colors hover:text-primary"
                to="#"
              >{{ link }}</ULink>
            </li>
          </ul>
        </div>

        <div>
          <h4 class="mb-6 font-bold text-white">{{ $t("footer.links") }}</h4>
          <ul class="space-y-3 text-sm">
            <li v-for="link in quickLinks" :key="link">
              <ULink
                class="block whitespace-nowrap transition-colors hover:text-primary"
                to="#"
              >{{ link }}</ULink>
            </li>
          </ul>
        </div>

        <div class="space-y-4">
          <h4 class="mb-2 text-base font-bold text-white">
            {{ $t("footer.newsletter") }}
          </h4>
          <p class="text-sm leading-relaxed text-slate-400">
            {{ $t("footer.newsletterDesc") }}
          </p>
          <form
            class="flex items-center gap-0 overflow-hidden rounded-xl border border-slate-700 bg-slate-800 transition-all duration-200 focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/60"
            @submit="handleNewsletterSubmit"
          >
            <input
              class="min-w-0 flex-1 bg-transparent px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none"
              :placeholder="$t('footer.emailPlaceholder')"
              type="email"
            />
            <button
              type="submit"
              class="flex shrink-0 items-center gap-2 bg-primary px-4 py-3 text-sm font-semibold text-white transition-all duration-200 hover:brightness-110"
            >
              {{ $t("footer.subscribe") }}
              <UIcon name="i-lucide-send" class="size-4" />
            </button>
          </form>
        </div>
      </div>

      <USeparator color="neutral" class="mb-10 opacity-20" />

      <div
        class="flex flex-col items-center justify-between gap-3 text-center text-xs text-slate-500 md:flex-row md:text-left"
      >
        <p>{{ $t("footer.copyright") }}</p>
        <div class="flex flex-wrap justify-center gap-x-6 gap-y-2 md:justify-end">
          <ULink class="hover:text-white" to="#">{{ $t("footer.terms") }}</ULink>
          <ULink class="hover:text-white" to="#">{{ $t("footer.privacy") }}</ULink>
        </div>
      </div>
    </UContainer>
  </footer>
</template>

<script setup lang="ts">
const { socials, serviceLinks, quickLinks, handleNewsletterSubmit } =
  useAppFooter();
</script>

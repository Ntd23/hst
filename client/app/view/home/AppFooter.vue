<template>
  <footer class="bg-[#0b1120] text-slate-300 py-12 sm:py-16">
    <UContainer>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-[minmax(250px,1.2fr)_minmax(200px,1fr)_minmax(200px,1fr)_minmax(320px,1.3fr)] gap-8 lg:gap-10 pb-10 sm:pb-12 mb-10 sm:mb-12">
        <div class="space-y-6">
          <h3 class="text-2xl font-black tracking-tighter text-white">HISOTECH</h3>
          <p class="text-sm text-slate-400 leading-relaxed">
            {{ $t('footer.description') }}
          </p>
          <div class="flex space-x-4">
            <ULink
              v-for="social in socials"
              :key="social.label"
              class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-primary hover:text-white transition-colors"
              to="#"
            >
              <span class="text-xs font-bold">{{ social.label }}</span>
            </ULink>
          </div>
        </div>

        <div>
          <h4 class="text-white font-bold mb-6">{{ $t('footer.services') }}</h4>
          <ul class="space-y-3 text-sm">
            <li v-for="link in serviceLinks" :key="link">
              <ULink class="block hover:text-primary transition-colors whitespace-nowrap" to="#">{{ link }}</ULink>
            </li>
          </ul>
        </div>

        <div>
          <h4 class="text-white font-bold mb-6">{{ $t('footer.links') }}</h4>
          <ul class="space-y-3 text-sm">
            <li v-for="link in quickLinks" :key="link">
              <ULink class="block hover:text-primary transition-colors whitespace-nowrap" to="#">{{ link }}</ULink>
            </li>
          </ul>
        </div>

        <div class="space-y-4">
          <h4 class="text-white font-bold text-base mb-2">{{ $t('footer.newsletter') }}</h4>
          <p class="text-sm text-slate-400 leading-relaxed">
            {{ $t('footer.newsletterDesc') }}
          </p>
          <form class="flex items-center gap-0 bg-slate-800 border border-slate-700 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-primary/60 focus-within:border-primary transition-all duration-200">
            <input
              class="flex-1 px-4 py-3 bg-transparent text-white text-sm placeholder-slate-500 focus:outline-none min-w-0"
              :placeholder="$t('footer.emailPlaceholder')"
              type="email"
            />
            <button
              type="submit"
              class="shrink-0 px-4 py-3 bg-primary hover:brightness-110 text-white text-sm font-semibold flex items-center gap-2 transition-all duration-200"
            >
              {{ $t('footer.subscribe') }}
              <UIcon name="i-lucide-send" class="size-4" />
            </button>
          </form>
        </div>
      </div>

      <USeparator color="neutral" class="opacity-20 mb-10" />

      <div class="flex flex-col md:flex-row justify-between items-center gap-3 text-xs text-slate-500 text-center md:text-left">
        <p>{{ $t('footer.copyright') }}</p>
        <div class="flex flex-wrap justify-center md:justify-end gap-x-6 gap-y-2">
          <ULink class="hover:text-white" to="#">{{ $t('footer.terms') }}</ULink>
          <ULink class="hover:text-white" to="#">{{ $t('footer.privacy') }}</ULink>
        </div>
      </div>
    </UContainer>
  </footer>
</template>

<script setup lang="ts">
const socials = [
  { label: 'f' },
  { label: 'in' },
  { label: 'yt' },
]

const { t } = useI18n()
const serviceLinks = computed(() => [
  t('footer.serviceLinks.software'),
  t('footer.serviceLinks.consulting'),
  t('footer.serviceLinks.design'),
  t('footer.serviceLinks.security'),
])
const quickLinks = computed(() => [
  t('footer.quickLinks.aboutUs'),
  t('footer.quickLinks.careers'),
  t('footer.quickLinks.blog'),
  t('footer.quickLinks.privacyPolicy'),
])
</script>

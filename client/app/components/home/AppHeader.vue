<template>
  <nav
    :class="[
      'fixed w-full z-50 top-0 transition-all duration-300 px-3 sm:px-4',
      isScrolled ? 'py-3' : 'py-4',
    ]"
  >
    <div
      :class="[
        'glass-panel glass-nav rounded-2xl mx-auto px-4 sm:px-6 py-2.5 sm:py-3 flex justify-between items-center transition-all duration-300',
        isScrolled ? 'glass-nav-scrolled' : '',
      ]"
    >
      <div class="flex items-center gap-2">
        <span class="text-xl sm:text-2xl font-black tracking-tighter text-primary">HISOTECH</span>
      </div>

      <!-- Desktop nav — custom links, full control -->
      <ul class="hidden lg:flex items-center gap-1">
        <li v-for="item in navItems" :key="item.label">
          <NuxtLink
            :to="item.to"
            class="nav-item-desktop"
          >
            <span class="nav-item-text">{{ item.label }}</span>
          </NuxtLink>
        </li>
      </ul>

      <div class="flex items-center gap-3 lg:gap-4">
        <!-- Language pill toggle -->
        <div class="hidden md:flex items-center relative bg-slate-100 dark:bg-slate-800 rounded-full p-0.5 border border-slate-200/60 dark:border-slate-700/60">
          <!-- Sliding indicator -->
          <div
            class="absolute top-0.5 h-[calc(100%-4px)] rounded-full bg-white dark:bg-slate-700 shadow-sm transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)]"
            :style="{ width: 'calc(50% - 2px)', left: locale === 'vi' ? '2px' : 'calc(50% + 2px)' }"
          />
          <button
            v-for="loc in availableLocales"
            :key="loc.code"
            class="relative z-10 flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-full transition-colors duration-300"
            :class="locale === loc.code ? 'text-primary' : 'text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300'"
            @click="switchLocaleWithReload(loc.code)"
          >
            {{ loc.code.toUpperCase() }}
          </button>
        </div>

        <NuxtLink
          to="#"
          class="hidden md:flex items-center px-4 py-2 rounded-xl text-[0.9375rem] font-semibold text-slate-700 hover:text-primary transition-colors duration-300 relative nav-login-btn"
        >
          {{ $t('nav.login') }}
        </NuxtLink>

        <UButton
          color="primary"
          variant="solid"
          size="md"
          class="hidden md:block rounded-xl font-semibold whitespace-nowrap btn-primary-lift-sm"
        >
          {{ $t('nav.contact') }}
        </UButton>

        <!-- Mobile locale toggle — compact, next to hamburger -->
        <button
          class="lg:hidden flex items-center justify-center w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700/60 text-xs font-bold text-primary transition-all duration-200 active:scale-90"
          @click="switchLocaleWithReload(locale === 'vi' ? 'en' : 'vi')"
        >
          {{ locale === 'vi' ? 'EN' : 'VN' }}
        </button>

        <UButton
          color="neutral"
          variant="ghost"
          :icon="isMobileMenuOpen ? 'i-lucide-x' : 'i-lucide-menu'"
          size="lg"
          class="lg:hidden nav-muted"
          :aria-expanded="isMobileMenuOpen"
          aria-label="Toggle mobile menu"
          @click="isMobileMenuOpen = !isMobileMenuOpen"
        />
      </div>
    </div>

    <!-- Mobile menu -->
    <Transition
      enter-active-class="transition-all duration-300 ease-out"
      enter-from-class="opacity-0 -translate-y-2"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition-all duration-200 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-2"
    >
      <div
        v-if="isMobileMenuOpen"
        class="lg:hidden mt-3 glass-panel glass-nav rounded-2xl mx-auto p-5 sm:p-6"
      >
        <ul class="space-y-1">
          <li v-for="item in navItems" :key="item.label">
            <NuxtLink
              :to="item.to"
              class="nav-item-mobile"
              @click="isMobileMenuOpen = false"
            >
              {{ item.label }}
            </NuxtLink>
          </li>
        </ul>
        <div class="pt-4 mt-3 border-t border-slate-200/50 dark:border-slate-700/50 grid grid-cols-1 sm:grid-cols-2 gap-3">
          <NuxtLink
            to="#"
            class="flex items-center justify-center px-4 py-3 rounded-xl text-base font-semibold text-slate-700 border border-slate-200 dark:border-slate-600 hover:border-primary hover:text-primary transition-all duration-300"
          >
            {{ $t('nav.login') }}
          </NuxtLink>
          <UButton
            color="primary"
            variant="solid"
            size="lg"
            class="w-full rounded-xl font-semibold justify-center btn-primary-lift-sm"
          >
            {{ $t('nav.contact') }}
          </UButton>
        </div>
      </div>
    </Transition>
  </nav>
</template>

<script setup lang="ts">
const isScrolled = ref(false)
const isMobileMenuOpen = ref(false)

const { locale, locales, setLocale } = useI18n()
const availableLocales = computed(() =>
  (locales.value as Array<{ code: string; name: string }>)
)

const { t } = useI18n()
const switchLocalePath = useSwitchLocalePath()

const switchLocaleWithReload = (code: string) => {
  const path = switchLocalePath(code)
  if (import.meta.client) {
    window.location.href = path
  }
}

const navItems = computed(() => [
  { label: t('nav.home'), to: '#' },
  { label: t('nav.products'), to: '#' },
  { label: t('nav.software'), to: '#' },
  { label: t('nav.about'), to: '#' },
  { label: t('nav.news'), to: '#' },
])

const handleScroll = () => {
  isScrolled.value = window.scrollY > 12
}

const handleResize = () => {
  if (window.innerWidth >= 1024) {
    isMobileMenuOpen.value = false
  }
}

onMounted(() => {
  handleScroll()
  handleResize()
  window.addEventListener('scroll', handleScroll, { passive: true })
  window.addEventListener('resize', handleResize, { passive: true })
})

onBeforeUnmount(() => {
  window.removeEventListener('scroll', handleScroll)
  window.removeEventListener('resize', handleResize)
})
</script>

<style scoped>
/* ===========================
   Desktop Nav Items
   Text 16px, hover: text becomes gradient + bold
   =========================== */
.nav-item-desktop {
  display: flex;
  align-items: center;
  padding: 0.5rem 1rem;
  border-radius: 0.625rem;
  font-size: 1.125rem;
  font-weight: 500;
  color: #334155;
  text-decoration: none;
  transition: color 0.3s ease;
  position: relative;
}

.nav-item-desktop .nav-item-text {
  position: relative;
  transition: font-weight 0.2s ease;
}

/* Hover: text becomes primary + bolder */
.nav-item-desktop:hover {
  color: var(--color-primary);
  font-weight: 600;
}

/* Active dot indicator below */
.nav-item-desktop::after {
  content: '';
  position: absolute;
  bottom: 2px;
  left: 50%;
  transform: translateX(-50%);
  width: 0;
  height: 1px;
  border-radius: 2px;
  background: linear-gradient(90deg, var(--color-primary), var(--color-secondary));
  transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.nav-item-desktop:hover::after {
  width: 50%;
}

/* ===========================
   Mobile Nav Items
   Text 18px, bold, pill hover bg
   =========================== */
.nav-item-mobile {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.875rem 1rem;
  border-radius: 0.75rem;
  font-size: 1.125rem;
  font-weight: 500;
  color: #334155;
  text-decoration: none;
  transition: all 0.25s ease;
  position: relative;
  border-left: 3px solid transparent;
}

.nav-item-mobile:hover,
.nav-item-mobile:active {
  color: var(--color-primary);
  font-weight: 600;
  background: rgba(0, 124, 195, 0.06);
  border-left-color: var(--color-primary);
  padding-left: 1.25rem;
}

/* ===========================
   Login Button — underline grows from center
   =========================== */
.nav-login-btn::after {
  content: '';
  position: absolute;
  bottom: 4px;
  left: 50%;
  transform: translateX(-50%);
  width: 0;
  height: 2px;
  border-radius: 1px;
  background: var(--color-primary);
  transition: width 0.3s ease;
}

.nav-login-btn:hover::after {
  width: 60%;
}
</style>

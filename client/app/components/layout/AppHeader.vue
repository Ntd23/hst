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
        <NuxtLink :to="headerData?.logo?.home_url || '/'">
          <img v-if="headerData?.logo?.logo" :src="headerData.logo.logo" :alt="headerData?.logo?.site_title" class="h-10 w-auto" />
          <span v-else class="text-xl sm:text-2xl font-black tracking-tighter text-primary">
            {{ headerData?.logo?.site_title || 'HISOTECH' }}
          </span>
        </NuxtLink>
      </div>

      <!-- Desktop nav — custom links, full control -->
      <ul class="hidden lg:flex items-center gap-1">
        <li
          v-for="item in computedNavItems"
          :key="item.id || item.title"
          class="relative"
          @mouseenter="activeDropdown = item.id ?? item.title"
          @mouseleave="activeDropdown = null"
        >
          <!-- Item có children -->
          <template v-if="item.has_children && item.children?.length">
            <NuxtLink :to="item.url || item.to" class="nav-item-desktop flex items-center">
              <span class="nav-item-text" :data-text="item.title || item.label">{{ item.title || item.label }}</span>
              <UIcon
                name="i-heroicons-chevron-down-20-solid"
                class="w-4 h-4 ml-1 opacity-50 transition-transform duration-200"
                :class="activeDropdown === (item.id ?? item.title) ? 'rotate-180' : ''"
              />
            </NuxtLink>

            <!-- Dropdown panel -->
            <Transition
              enter-active-class="transition-all duration-200 ease-out"
              enter-from-class="opacity-0 translate-y-1"
              enter-to-class="opacity-100 translate-y-0"
              leave-active-class="transition-all duration-150 ease-in"
              leave-from-class="opacity-100 translate-y-0"
              leave-to-class="opacity-0 translate-y-1"
            >
              <ul
                v-show="activeDropdown === (item.id ?? item.title)"
                class="nav-dropdown"
              >
                <li v-for="child in item.children" :key="child.id">
                  <NuxtLink :to="child.url" class="nav-dropdown-item">
                    <UIcon name="i-heroicons-chevron-right-20-solid" class="nav-dropdown-icon" />
                    <span>{{ child.title }}</span>
                  </NuxtLink>
                </li>
              </ul>
            </Transition>
          </template>

          <!-- Item thông thường -->
          <NuxtLink v-else :to="item.url || item.to" class="nav-item-desktop">
            <span class="nav-item-text" :data-text="item.title || item.label">{{ item.title || item.label }}</span>
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
            @click="switchLocale(loc.code)"
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
          @click="switchLocale(locale === 'vi' ? 'en' : 'vi')"
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
          <li v-for="item in computedNavItems" :key="item.id || item.title">
            <template v-if="item.has_children && item.children?.length">
              <div class="nav-item-mobile flex justify-between items-center opacity-80">
                <span>{{ item.title || item.label }}</span>
                <UIcon name="i-heroicons-chevron-down-20-solid" class="w-5 h-5" />
              </div>
              <ul class="pl-4 border-l border-slate-200/50 dark:border-slate-700/50 mt-1 space-y-1">
                <li v-for="child in item.children" :key="child.id">
                  <NuxtLink
                    :to="child.url"
                    class="nav-item-mobile !text-[1rem] !py-2"
                    @click="isMobileMenuOpen = false"
                  >
                    {{ child.title }}
                  </NuxtLink>
                </li>
              </ul>
            </template>
            <NuxtLink
              v-else
              :to="item.url || item.to"
              class="nav-item-mobile"
              @click="isMobileMenuOpen = false"
            >
              {{ item.title || item.label }}
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
import { useHeader } from '~/composables/common/useHeader';

const isScrolled = ref(false)
const isMobileMenuOpen = ref(false)
const activeDropdown = ref<string | number | null>(null)

const { locale, locales, setLocale } = useI18n()
const availableLocales = computed(() =>
  (locales.value as Array<{ code: string; name: string }>)
)

const { t } = useI18n()
const switchLocalePath = useSwitchLocalePath()

const { data: headerResponse } = await useHeader()
const headerData = computed(() => headerResponse.value?.data || headerResponse.value || null)

const switchLocale = (code: string) => {
  const path = switchLocalePath(code as 'vi' | 'en')
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

const computedNavItems = computed(() => {
  if (headerData.value?.main_menu?.items) {
    return headerData.value.main_menu.items
  }
  return navItems.value
})

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
  transition: color 0.2s ease;
}

/* Phantom bold — reserve space for bold text so width doesn't change on hover */
.nav-item-desktop .nav-item-text::before {
  content: attr(data-text);
  font-weight: 600;
  visibility: hidden;
  height: 0;
  overflow: hidden;
  display: block;
  pointer-events: none;
  user-select: none;
}

/* Hover: text becomes primary, NO font-weight change to avoid layout shift */
.nav-item-desktop:hover {
  color: var(--color-primary);
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

/* ===========================
   Dropdown panel — glass-nav style
   =========================== */
.nav-dropdown {
  position: absolute;
  top: calc(100% + 10px);
  left: 50%;
  transform: translateX(-50%);
  min-width: 230px;

  /* Khớp với glass-nav */
  background: rgba(248, 250, 252, 0.96);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.88);
  box-shadow:
    0 10px 30px rgba(15, 23, 42, 0.1),
    0 0 0 1px rgba(0, 124, 195, 0.06);

  /* Accent border trên */
  border-top: 2px solid transparent;
  background-clip: padding-box;
  border-radius: 0 0 1rem 1rem;

  padding: 0.375rem;
  z-index: 100;
  list-style: none;

  /* Caret */
  &::before {
    content: '';
    position: absolute;
    top: -7px;
    left: 50%;
    transform: translateX(-50%);
    width: 12px;
    height: 12px;
    background: rgba(248, 250, 252, 0.96);
    border-left: 1px solid rgba(255, 255, 255, 0.88);
    border-top: 1px solid rgba(255, 255, 255, 0.88);
    clip-path: polygon(0 100%, 50% 0, 100% 100%);
  }

  /* Primary accent top border */
  &::after {
    content: '';
    position: absolute;
    top: -2px;
    left: 0;
    right: 0;
    height: 2px;
    border-radius: 2px 2px 0 0;
    background: linear-gradient(90deg, var(--color-primary), #00a8e8);
  }
}

.dark .nav-dropdown {
  background: rgba(15, 23, 42, 0.95);
  border-color: rgba(148, 163, 184, 0.22);
  box-shadow: 0 10px 30px rgba(2, 6, 23, 0.4);

  &::before {
    background: rgba(15, 23, 42, 0.95);
    border-color: rgba(148, 163, 184, 0.22);
  }
}

.nav-dropdown-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.625rem 0.875rem;
  border-radius: 0.625rem;
  font-size: 0.9375rem;
  font-weight: 500;
  color: #334155;
  text-decoration: none;
  white-space: nowrap;
  transition: all 0.2s ease;
}

.dark .nav-dropdown-item {
  color: #cbd5e1;
}

.nav-dropdown-item:hover {
  color: var(--color-primary);
  background: rgba(0, 124, 195, 0.07);
}

.nav-dropdown-icon {
  width: 0.875rem;
  height: 0.875rem;
  opacity: 0.35;
  flex-shrink: 0;
  transition: transform 0.2s ease, opacity 0.2s ease;
}

.nav-dropdown-item:hover .nav-dropdown-icon {
  opacity: 1;
  transform: translateX(2px);
}
</style>

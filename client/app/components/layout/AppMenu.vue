<template>
  <header
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
        <NuxtLink :to="menuData?.logo?.home_url || '/'">
          <img
            v-if="menuData?.logo?.logo"
            :src="menuData.logo.logo"
            :alt="menuData?.logo?.site_title"
            class="h-7 sm:h-10 w-auto"
          />
          <span
            v-else
            class="text-xl sm:text-2xl font-black tracking-tighter text-primary"
          >
            {{ menuData?.logo?.site_title || "HISOTECH" }}
          </span>
        </NuxtLink>
      </div>

      <ul class="hidden lg:flex items-center gap-1">
        <li
          v-for="item in computedNavItems"
          :key="item.id || item.title"
          class="relative"
          @mouseenter="activeDropdown = item.id ?? item.title"
          @mouseleave="activeDropdown = null"
        >
          <template v-if="item.has_children && item.children?.length">
            <NuxtLink
              :to="item.url || item.to"
              class="nav-item-desktop flex items-center"
            >
              <span
                class="nav-item-text"
                :data-text="item.title || item.label"
                >{{ item.title || item.label }}</span
              >
              <UIcon
                name="i-heroicons-chevron-down-20-solid"
                class="w-4 h-4 ml-1 opacity-50 transition-transform duration-200"
                :class="
                  activeDropdown === (item.id ?? item.title) ? 'rotate-180' : ''
                "
              />
            </NuxtLink>

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
                    <UIcon
                      name="i-heroicons-chevron-right-20-solid"
                      class="nav-dropdown-icon"
                    />
                    <span>{{ child.title }}</span>
                  </NuxtLink>
                </li>
              </ul>
            </Transition>
          </template>

          <NuxtLink v-else :to="item.url || item.to" class="nav-item-desktop">
            <span class="nav-item-text" :data-text="item.title || item.label">{{
              item.title || item.label
            }}</span>
          </NuxtLink>
        </li>
      </ul>

      <div class="flex items-center gap-3 lg:gap-4">
        <div
          class="hidden md:flex items-center relative bg-slate-100 dark:bg-slate-800 rounded-full p-0.5 border border-slate-200/60 dark:border-slate-700/60"
        >
          <div
            class="absolute top-0.5 h-[calc(100%-4px)] rounded-full bg-white dark:bg-slate-700 shadow-sm transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)]"
            :style="{
              width: 'calc(50% - 2px)',
              left: locale === 'vi' ? '2px' : 'calc(50% + 2px)',
            }"
          />
          <button
            v-for="loc in availableLocales"
            :key="loc.code"
            class="relative z-10 flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-full transition-colors duration-300"
            :class="
              locale === loc.code
                ? 'text-primary'
                : 'text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300'
            "
            @click="switchLocale(loc.code)"
          >
            {{ loc.code.toUpperCase() }}
          </button>
        </div>

        <NuxtLink
          to="#"
          class="hidden md:flex items-center px-4 py-2 rounded-xl text-[0.9375rem] font-semibold text-slate-700 hover:text-primary transition-colors duration-300 relative nav-login-btn"
        >
          {{ $t("nav.login") }}
        </NuxtLink>

        <UButton
          :to="contactButtonLink"
          color="primary"
          variant="solid"
          size="md"
          class="hidden md:block rounded-xl font-semibold whitespace-nowrap btn-primary-lift-sm"
        >
          {{ $t("nav.contact") }}
        </UButton>

        <button
          class="lg:hidden flex items-center justify-center w-8 h-8 rounded-lg border border-primary/40 text-[11px] font-bold text-primary hover:bg-primary/10 transition-all duration-200 active:scale-90"
          @click="switchLocale(locale === 'vi' ? 'en' : 'vi')"
        >
          {{ locale === "vi" ? "EN" : "VN" }}
        </button>

        <UButton
          color="neutral"
          variant="ghost"
          :icon="isMobileMenuOpen ? 'i-lucide-x' : 'i-lucide-menu'"
          size="lg"
          class="lg:hidden"
          :aria-expanded="isMobileMenuOpen"
          aria-label="Toggle mobile menu"
          @click="isMobileMenuOpen = !isMobileMenuOpen"
        />
      </div>
    </div>

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
              <div
                class="nav-item-mobile flex justify-between items-center opacity-80"
              >
                <span>{{ item.title || item.label }}</span>
                <UIcon
                  name="i-heroicons-chevron-down-20-solid"
                  class="w-5 h-5"
                />
              </div>
              <ul
                class="pl-4 border-l border-slate-200/50 dark:border-slate-700/50 mt-1 space-y-1"
              >
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
        <div
          class="pt-4 mt-3 border-t border-slate-200/50 dark:border-slate-700/50 grid grid-cols-1 sm:grid-cols-2 gap-3"
        >
          <NuxtLink
            to="#"
            class="flex items-center justify-center px-4 py-3 rounded-xl text-base font-semibold text-slate-700 border border-slate-200 dark:border-slate-600 hover:border-primary hover:text-primary transition-all duration-300"
          >
            {{ $t("nav.login") }}
          </NuxtLink>
          <UButton
            :to="contactButtonLink"
            color="primary"
            variant="solid"
            size="lg"
            class="w-full rounded-xl font-semibold justify-center btn-primary-lift-sm"
            @click="isMobileMenuOpen = false"
          >
            {{ $t("nav.contact") }}
          </UButton>
        </div>
      </div>
    </Transition>
  </header>
</template>

<script setup lang="ts">
import { useAppMenu } from "~/composables/layout/useAppMenu";

const {
  isScrolled,
  isMobileMenuOpen,
  activeDropdown,
  locale,
  availableLocales,
  menuData,
  computedNavItems,
  contactButtonLink,
  switchLocale,
} = useAppMenu();
</script>

<style scoped>
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

.nav-item-desktop:hover {
  color: var(--color-primary);
}

.nav-item-desktop::after {
  content: "";
  position: absolute;
  bottom: 2px;
  left: 50%;
  transform: translateX(-50%);
  width: 0;
  height: 1px;
  border-radius: 2px;
  background: linear-gradient(
    90deg,
    var(--color-primary),
    var(--color-secondary)
  );
  transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.nav-item-desktop:hover::after {
  width: 50%;
}

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

.nav-login-btn::after {
  content: "";
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

.nav-dropdown {
  position: absolute;
  top: calc(100% + 10px);
  left: 0;
  min-width: 240px;
  padding: 0.75rem;
  border-radius: 1rem;
  background: rgba(255, 255, 255, 0.82);
  border: 1px solid rgba(226, 232, 240, 0.8);
  box-shadow: 0 20px 40px rgba(15, 23, 42, 0.12);
  backdrop-filter: blur(16px);
}

.nav-dropdown-item {
  display: flex;
  align-items: center;
  gap: 0.625rem;
  padding: 0.75rem 0.875rem;
  border-radius: 0.875rem;
  color: #334155;
  text-decoration: none;
  transition: all 0.2s ease;
}

.nav-dropdown-item:hover {
  color: var(--color-primary);
  background: rgba(0, 124, 195, 0.06);
}

.nav-dropdown-icon {
  width: 1rem;
  height: 1rem;
  opacity: 0.5;
}
</style>

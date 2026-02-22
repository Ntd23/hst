<template>
  <div class="min-h-screen bg-slate-50 text-slate-900">
		<HeaderTopBar :data="topbarData" />
    <MainNav :items="navItems" />

    <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
      <slot />
    </main>

    <footer class="border-t bg-white">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 text-sm text-slate-500">
        © {{ new Date().getFullYear() }} HST
      </div>
    </footer>
  </div>
</template>

<script setup lang="ts">
import MainNav from '~/components/navigation/MainNav.vue'
import HeaderTopBar from '~/components/header/HeaderTopBar.vue'

const lang = useCookie('i18n_redirected').value || useCookie('locale').value || 'vi'

const { data: nav } = await useNavigation('main-menu', { absolute: true, lang })
const navItems = computed(() => nav.value?.data ?? [])


const { data: topbar } = await useHeaderTopBar()
const topbarData = computed(() => topbar.value?.data ?? { phone: null, email: null, address: null })
</script>

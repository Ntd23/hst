<template>
  <header style="padding:12px 16px;border-bottom:1px solid #eee;">
    <nav v-if="nav?.data?.length" style="display:flex;gap:12px;flex-wrap:wrap;">
      <NuxtLink v-for="i in nav.data" :key="i.id" :to="i.url" :target="i.target">
        {{ i.title }}
      </NuxtLink>
    </nav>
  </header>
</template>

<script setup lang="ts">
const { data: nav } = await useNavigationApiFetch('main-menu', false)
</script>
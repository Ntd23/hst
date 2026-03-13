<template>
  <div class="error-page">
    <h1 class="text-9xl font-extrabold text-[#7CB342]">{{ error?.statusCode || 404 }}</h1>
    <h2 class="mt-4 text-2xl font-semibold text-gray-800 uppercase tracking-widest">
      {{ error?.statusMessage || 'Page Not Found' }}</h2>
    <button @click="handleError">Về trang chủ</button>
  </div>
</template>

<script setup lang="ts">
const props = defineProps({
  error: Object
})

const handleError = () => clearError({ redirect: '/' })
</script>

<style scoped>
.error-page {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
}
</style>

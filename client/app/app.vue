<!-- app/app.vue -->
<template>
  <UApp>
    <NuxtLayout>
      <NuxtPage />
    </NuxtLayout>
  </UApp>
</template>

<script setup lang="ts">
// Inject inline style immediately into <head> to prevent FOUC
// body starts invisible, gets fade-in class after Vue mounts
useHead({
  style: [
    {
      innerHTML: `
        body { opacity: 0; }
        body.css-ready { opacity: 1; transition: opacity 0.2s ease; }
      `,
      id: 'fouc-prevention',
    },
  ],
})

onMounted(() => {
  // Small nextTick to ensure CSS is applied before revealing
  nextTick(() => {
    document.body.classList.add('css-ready')
  })
})
</script>

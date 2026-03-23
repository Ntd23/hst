<template>
  <UApp class="bg-layer">
    <NuxtLoadingIndicator color="#007CC3" :height="3" />
    <NuxtLayout>
      <NuxtPage />
    </NuxtLayout>
  </UApp>
</template>

<script setup lang="ts">

</script>

<style>
.bg-layer {
  background:
    radial-gradient(ellipse 80% 60% at 20% 0%, rgba(186,230,255,0.4) 0%, transparent 60%),
    radial-gradient(ellipse 60% 50% at 80% 100%, rgba(167,243,208,0.3) 0%, transparent 60%),
    #f0f9ff;
  min-height: 100vh;
}
</style>

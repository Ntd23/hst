<style scoped>
.menu-top {
  height: 100px;
}
</style>
<template>
  <div
    class="font-sans antialiased text-slate-700 dark:text-slate-200 bg-pastel-gradient min-h-screen selection:bg-secondary selection:text-white transition-colors duration-300"
  >
    <div class="menu-top">
      <LayoutAppMenu />
    </div>
    <slot />
    <LayoutAppWidget />
  </div>
</template>


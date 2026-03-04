<template>
  <div
    class="font-sans antialiased text-slate-700 dark:text-slate-200 bg-pastel-gradient min-h-screen selection:bg-secondary selection:text-white transition-colors duration-300"
  >
    <div class="header-top">
      <LayoutAppHeader />
    </div>
    <div>
      <slot />
    </div>
    <LayoutAppFooter />
  </div>
</template>
<style scoped>
.header-top {
  height: 70px;
}
</style>
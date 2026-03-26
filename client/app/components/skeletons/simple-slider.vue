<template>
  <header
    class="relative h-screen min-h-[560px] max-h-[960px] overflow-hidden bg-slate-950"
  >
    <!-- ===== Background skeleton ===== -->
    <div class="absolute inset-0">
      <div class="w-full h-full bg-slate-800 animate-pulse" />

      <!-- overlay giống thật -->
      <div
        class="absolute inset-0 bg-gradient-to-b from-slate-950/60 via-slate-950/30 to-slate-950/70"
      />
    </div>

    <!-- ===== Center content skeleton ===== -->
    <div
      class="relative z-10 h-full flex items-center justify-center px-5 sm:px-8"
    >
      <div class="text-center max-w-3xl mx-auto w-full">
        <!-- subtitle skeleton -->
        <div class="flex items-center justify-center gap-3 mb-6">
          <span class="h-px w-8 bg-white/20 animate-pulse" />

          <div class="h-3 w-40 bg-white/10 rounded animate-pulse" />

          <span class="h-px w-8 bg-white/20 animate-pulse" />
        </div>

        <!-- title skeleton -->
        <div class="space-y-3 mb-5">
          <div
            class="h-8 sm:h-10 md:h-12 lg:h-14 bg-white/10 rounded w-3/4 mx-auto animate-pulse"
          />
          <div
            class="h-8 sm:h-10 md:h-12 lg:h-14 bg-white/10 rounded w-2/3 mx-auto animate-pulse"
          />
        </div>

        <!-- description skeleton -->
        <div class="space-y-2 mb-9 max-w-xl mx-auto">
          <div class="h-3 bg-white/10 rounded animate-pulse w-full" />
          <div class="h-3 bg-white/10 rounded animate-pulse w-5/6 mx-auto" />
          <div class="h-3 bg-white/10 rounded animate-pulse w-2/3 mx-auto" />
        </div>

        <!-- button skeleton -->
        <div class="flex justify-center">
          <div class="h-11 w-44 bg-white/10 rounded-full animate-pulse" />
        </div>
      </div>
    </div>

    <!-- ===== bottom progress skeleton ===== -->
    <div class="absolute bottom-0 left-0 right-0 h-[3px] bg-white/[0.06]">
      <div class="h-full w-1/3 bg-white/20 animate-pulse" />
    </div>
  </header>
</template>
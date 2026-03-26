<template>
  <section class="relative py-20 lg:py-32 overflow-hidden bg-white">
    <UContainer class="relative z-10">
      <!-- ===== Header skeleton ===== -->
      <div class="text-center mb-16 lg:mb-24">
        <div
          class="h-10 lg:h-14 w-2/3 mx-auto bg-slate-200 rounded-xl animate-pulse mb-6"
        />
        <div class="h-4 w-3/5 mx-auto bg-slate-200 rounded animate-pulse" />
        <div
          class="h-4 w-2/5 mx-auto bg-slate-200 rounded animate-pulse mt-2"
        />
      </div>

      <div
        class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center"
      >
        <!-- ===== LEFT skeleton (questions) ===== -->
        <div class="space-y-6">
          <div
            v-for="i in 5"
            :key="i"
            class="flex items-center gap-5 p-6 rounded-3xl bg-slate-100 animate-pulse"
          >
            <!-- index -->
            <div class="w-12 h-12 rounded-2xl bg-slate-200" />

            <!-- text -->
            <div class="flex-1 space-y-2">
              <div class="h-4 w-3/4 bg-slate-200 rounded" />
              <div class="h-3 w-1/2 bg-slate-200 rounded" />
            </div>

            <!-- arrow -->
            <div class="w-6 h-6 bg-slate-200 rounded" />
          </div>
        </div>

        <!-- ===== RIGHT skeleton (answer portal) ===== -->
        <div class="relative min-h-[400px] flex items-center justify-center">
          <div class="relative w-full aspect-square max-w-[500px]">
            <!-- glass core -->
            <div
              class="absolute inset-0 rounded-[3rem] bg-slate-100 p-8 lg:p-12 flex flex-col justify-center animate-pulse"
            >
              <!-- badge -->
              <div class="flex items-center gap-3 mb-6">
                <div class="h-6 w-28 bg-slate-200 rounded-full" />
                <div class="h-px flex-1 bg-slate-200" />
              </div>

              <!-- answer lines -->
              <div class="space-y-3">
                <div class="h-4 bg-slate-200 rounded w-full" />
                <div class="h-4 bg-slate-200 rounded w-5/6" />
                <div class="h-4 bg-slate-200 rounded w-4/6" />
                <div class="h-4 bg-slate-200 rounded w-3/6" />
              </div>

              <!-- floating block skeleton -->
              <div
                class="absolute -top-10 -right-6 w-36 h-36 bg-slate-200 rounded-3xl animate-pulse opacity-60"
              />
            </div>

            <!-- orbit skeleton -->
            <div class="absolute inset-0">
              <div
                class="w-full h-full border border-slate-200 rounded-[3rem] animate-pulse opacity-40"
              />
            </div>
          </div>
        </div>
      </div>
    </UContainer>
  </section>
</template>
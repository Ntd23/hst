<template>
  <section class="testimonials-highlight relative py-6 overflow-hidden">
    <!-- Ambient decor -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden h-full">
      <div
        class="absolute top-10 left-10 w-40 h-40 bg-gray-200/40 rounded-full blur-2xl animate-pulse"
      />
      <div
        class="absolute bottom-10 right-10 w-52 h-52 bg-gray-200/30 rounded-full blur-2xl animate-pulse"
      />
    </div>

    <UContainer class="relative z-10 px-0">
      <!-- HEADER -->
      <div class="text-center mb-6 px-4 space-y-3">
        <div class="h-4 w-28 bg-gray-200 rounded-full mx-auto animate-pulse" />
        <div class="h-8 w-2/3 bg-gray-200 rounded-lg mx-auto animate-pulse" />
      </div>

      <!-- CAROUSEL -->
      <div class="relative w-full">
        <!-- 3 cards visible -->
        <div class="flex gap-6 overflow-hidden py-4 px-6 lg:px-20">
          <div v-for="i in 3" :key="i" class="flex-1 min-w-0">
            <div
              class="rounded-[3rem] p-10 lg:p-12 bg-gray-200 animate-pulse h-[420px] flex flex-col justify-between"
            >
              <!-- stars -->
              <div class="flex justify-center gap-1.5 mb-6">
                <div
                  v-for="s in 5"
                  :key="s"
                  class="w-4 h-4 rounded bg-gray-300 animate-pulse"
                />
              </div>

              <!-- quote -->
              <div class="space-y-3 text-center">
                <div
                  class="h-4 w-full bg-gray-300 rounded animate-pulse mx-auto"
                />
                <div
                  class="h-4 w-5/6 bg-gray-300 rounded animate-pulse mx-auto"
                />
                <div
                  class="h-4 w-4/6 bg-gray-300 rounded animate-pulse mx-auto"
                />
              </div>

              <!-- author -->
              <div
                class="mt-10 pt-8 border-t border-gray-300 flex flex-col items-center space-y-3"
              >
                <div
                  class="w-16 h-16 rounded-[1.25rem] bg-gray-300 animate-pulse"
                />

                <div class="h-5 w-28 bg-gray-300 rounded animate-pulse" />
                <div class="h-3 w-20 bg-gray-300 rounded animate-pulse" />
              </div>
            </div>
          </div>
        </div>

        <!-- DOTS -->
        <div class="flex justify-center gap-2.5 mt-6">
          <div
            v-for="i in 3"
            :key="i"
            class="h-2 w-2 rounded-full bg-gray-300 animate-pulse"
          />
        </div>
      </div>
    </UContainer>
  </section>
</template>
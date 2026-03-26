<template>
  <section class="services-section relative py-12 overflow-hidden">
    <UContainer class="relative z-10">
      <!-- HEADER SKELETON -->
      <div class="text-center mb-12 sm:mb-18">
        <div class="inline-flex items-center gap-2 mb-4 justify-center">
          <div class="h-5 w-32 bg-gray-200 rounded-full animate-pulse" />
        </div>

        <div
          class="h-10 w-2/3 mx-auto bg-gray-200 rounded-lg animate-pulse mb-4"
        />

        <div class="h-4 w-1/2 mx-auto bg-gray-200 rounded animate-pulse mb-2" />
        <div class="h-4 w-1/3 mx-auto bg-gray-200 rounded animate-pulse" />

        <div class="mt-8">
          <div
            class="h-11 w-40 mx-auto bg-gray-200 rounded-full animate-pulse"
          />
        </div>
      </div>

      <!-- GRID -->
      <div class="services-wrapper">
        <!-- FEATURED SKELETON -->
        <div
          class="bento-card bento-featured relative overflow-hidden h-[320px] lg:h-auto bg-gray-200 animate-pulse rounded-2xl"
        >
          <div class="absolute inset-0 bg-gray-300/60" />

          <div class="absolute top-4 left-4">
            <div class="h-5 w-24 bg-gray-300 rounded-full animate-pulse" />
          </div>

          <div class="absolute inset-x-0 bottom-0 p-5 sm:p-6 space-y-3">
            <div class="h-5 w-2/3 bg-gray-300 rounded animate-pulse" />
            <div class="h-4 w-full bg-gray-300 rounded animate-pulse" />
            <div class="h-4 w-5/6 bg-gray-300 rounded animate-pulse" />

            <div class="h-8 w-28 bg-gray-300 rounded-full mt-4 animate-pulse" />
          </div>
        </div>

        <!-- CARDS ROW SKELETON -->
        <div
          class="cards-row grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-6"
        >
          <div
            v-for="i in 6"
            :key="i"
            class="bento-card relative overflow-hidden h-[260px] lg:h-[320px] bg-gray-200 rounded-2xl animate-pulse"
          >
            <div class="absolute inset-0 bg-gray-300/60" />

            <div class="absolute inset-x-0 bottom-0 p-4 sm:p-5 space-y-2">
              <div class="h-4 w-2/3 bg-gray-300 rounded animate-pulse" />
              <div class="h-3 w-full bg-gray-300 rounded animate-pulse" />
              <div class="h-3 w-4/5 bg-gray-300 rounded animate-pulse" />
            </div>
          </div>
        </div>
      </div>
    </UContainer>
  </section>
</template>
<template>
  <section class="team-section relative overflow-hidden py-14 sm:py-18">
    <UContainer class="relative z-10">
      <!-- HEADER SKELETON -->
      <div class="mx-auto mb-10 max-w-3xl text-center">
        <div class="flex justify-center mb-3">
          <div class="h-5 w-28 bg-gray-200 rounded-full animate-pulse" />
        </div>

        <div
          class="h-10 w-2/3 mx-auto bg-gray-200 rounded-lg animate-pulse mt-4"
        />
      </div>

      <!-- GRID SKELETON -->
      <div
        class="team-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5"
      >
        <article
          v-for="i in 6"
          :key="i"
          class="member-bento group relative overflow-hidden rounded-2xl bg-gray-200 animate-pulse h-[320px] sm:h-[360px]"
        >
          <!-- IMAGE AREA -->
          <div class="absolute inset-0 bg-gray-300/60" />

          <!-- OVERLAY -->
          <div
            class="absolute inset-0 bg-gradient-to-t from-gray-400/40 to-transparent"
          />

          <!-- SOCIAL SKELETON -->
          <div class="absolute top-3 right-3 flex gap-1.5">
            <div
              v-for="s in 3"
              :key="s"
              class="size-6 rounded-full bg-gray-300 animate-pulse"
            />
          </div>

          <!-- CONTENT -->
          <div class="absolute inset-x-0 bottom-0 p-3 sm:p-4 space-y-2">
            <!-- location -->
            <div class="h-3 w-24 bg-gray-300 rounded animate-pulse" />

            <!-- name -->
            <div class="h-5 w-2/3 bg-gray-300 rounded animate-pulse" />

            <!-- title -->
            <div class="h-3 w-1/2 bg-gray-300 rounded animate-pulse" />

            <!-- description -->
            <div class="h-3 w-full bg-gray-300 rounded animate-pulse" />
            <div class="h-3 w-4/5 bg-gray-300 rounded animate-pulse" />
          </div>
        </article>
      </div>
    </UContainer>
  </section>
</template>
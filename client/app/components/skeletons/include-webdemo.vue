<template>
  <section class="py-16">
    <UContainer>
      <!-- HEADER -->
      <div
        class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4 mb-10 sm:mb-12"
      >
        <div class="space-y-3">
          <div class="h-4 w-28 bg-gray-200 rounded animate-pulse" />
          <div class="h-7 w-52 bg-gray-200 rounded animate-pulse" />
        </div>

        <!-- arrows -->
        <div class="flex gap-2 mt-1 sm:mt-0">
          <div class="w-10 h-10 rounded-full bg-gray-200 animate-pulse" />
          <div class="w-10 h-10 rounded-full bg-gray-200 animate-pulse" />
        </div>
      </div>

      <!-- GRID -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="i in 6"
          :key="i"
          class="relative rounded-2xl overflow-hidden bg-gray-200 animate-pulse"
        >
          <!-- IMAGE AREA -->
          <div class="h-52 sm:h-56 lg:h-64 bg-gray-300" />

          <!-- OVERLAY -->
          <div
            class="absolute inset-x-0 bottom-0 p-5 space-y-3 bg-gradient-to-t from-gray-400/60 to-transparent"
          >
            <!-- title -->
            <div class="h-5 w-2/3 bg-gray-300 rounded" />
            <div class="h-5 w-1/2 bg-gray-300 rounded" />

            <!-- icon circle -->
            <div class="flex justify-end">
              <div class="w-10 h-10 rounded-full bg-gray-300" />
            </div>
          </div>
        </div>
      </div>
    </UContainer>
  </section>
</template>
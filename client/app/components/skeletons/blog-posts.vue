<template>
  <section class="py-16">
    <UContainer>
      <!-- HEADER -->
      <div
        class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4 mb-8 sm:mb-10"
      >
        <div class="space-y-3">
          <div class="h-4 w-28 bg-gray-200 rounded animate-pulse" />
          <div class="h-7 w-52 bg-gray-200 rounded animate-pulse" />
        </div>

        <div
          class="hidden md:block h-4 w-28 bg-gray-200 rounded animate-pulse"
        />
      </div>

      <!-- LISTING MODE SKELETON -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="i in 6"
          :key="i"
          class="rounded-2xl overflow-hidden bg-gray-200 animate-pulse flex flex-col"
        >
          <!-- image -->
          <div class="aspect-video bg-gray-300" />

          <!-- content -->
          <div class="p-5 flex flex-col flex-1 space-y-3">
            <!-- categories -->
            <div class="flex gap-2">
              <div class="h-4 w-16 bg-gray-300 rounded-full" />
              <div class="h-4 w-12 bg-gray-300 rounded-full" />
            </div>

            <!-- title -->
            <div class="h-5 w-full bg-gray-300 rounded" />
            <div class="h-5 w-4/5 bg-gray-300 rounded" />

            <!-- desc -->
            <div class="h-4 w-full bg-gray-300 rounded" />
            <div class="h-4 w-2/3 bg-gray-300 rounded" />

            <!-- footer -->
            <div
              class="mt-auto pt-3 border-t border-gray-300 flex justify-between"
            >
              <div class="h-3 w-20 bg-gray-300 rounded" />
              <div class="h-3 w-16 bg-gray-300 rounded" />
            </div>
          </div>
        </div>
      </div>
    </UContainer>
  </section>
</template>
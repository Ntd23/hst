<template>
  <section class="py-16 sm:py-20 lg:py-24 relative overflow-hidden">
    <UContainer>
      <div class="glass-panel rounded-3xl overflow-hidden relative">
        <div class="grid grid-cols-1 lg:grid-cols-2">
          <div class="relative h-80 sm:h-96 lg:h-auto">
            <img
              alt="Office discussion"
              class="absolute inset-0 w-full h-full object-cover"
              src="https://lh3.googleusercontent.com/aida-public/AB6AXuBCHUdoQzkyJyHSuXCOsT2GfJBZBTxu_AKMayMBmdyMmf2HtkGIzPZFlr7NsdSHCnLnGkbrlABQa5pFNooQ_JdErsHBcWrQoXXCF_XeG_WGk0fph97Qn9kOOcw4VJKC_NtJTX-HXKa55iyxHkbM8J51M6_pEJi1XnRJeCp3LbnDHyauKU26GhzW64f7LZbAuKAJI4sLMiBTjIbObnmGu6nCJw9aPBNtuhCwvKE5rFtQmn7vokbgG9aqLnf3_GcUj78lAT7g_g_ZQaU"
            />
            <div
              class="absolute inset-0 from-primary/80 to-transparent mix-blend-multiply"
            />
            <div
              class="absolute bottom-4 sm:bottom-8 left-4 sm:left-8 right-4 sm:right-8 glass-panel-darker p-4 sm:p-6 rounded-2xl"
            >
              <div class="flex items-center gap-4 mb-4">
                <div class="p-2.5 sm:p-3 bg-blue-100 rounded-full text-primary">
                  <UIcon name="i-lucide-users" class="size-5 sm:size-6" />
                </div>
                <div>
                  <h4 class="font-bold text-sm sm:text-base text-slate-800">
                    {{ $t("about.creativeTeam") }}
                  </h4>
                  <p class="text-sm text-slate-600">
                    {{ $t("about.creativeTeamDesc") }}
                  </p>
                </div>
              </div>
              <div class="flex items-center gap-4">
                <div
                  class="p-2.5 sm:p-3 bg-yellow-100 rounded-full text-secondary"
                >
                  <UIcon name="i-lucide-sun" class="size-5 sm:size-6" />
                </div>
                <div>
                  <h4 class="font-bold text-slate-800">
                    {{ $t("about.vision") }}
                  </h4>
                  <p class="text-sm text-slate-600">
                    {{ $t("about.visionDesc") }}
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div
            class="p-6 sm:p-10 lg:p-16 flex flex-col justify-center relative"
          >
            <div
              class="absolute top-0 right-0 w-64 h-64 bg-secondary/10 rounded-full blur-3xl -z-10 translate-x-1/2 -translate-y-1/2"
            />
            <span
              class="text-secondary font-semibold tracking-wide uppercase text-sm mb-4 block"
              >{{ $t("about.subtitle") }}</span
            >
            <h2
              class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white mb-6"
            >
              {{ $t("about.title") }}
            </h2>
            <p class="text-slate-600 dark:text-slate-400 mb-6 leading-relaxed">
              {{ $t("about.description1") }}
            </p>
            <p class="text-slate-600 dark:text-slate-400 mb-8 leading-relaxed">
              {{ $t("about.description2") }}
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
              <UButton
                color="primary"
                variant="solid"
                size="lg"
                trailing-icon="i-lucide-arrow-right"
                class="rounded-xl font-semibold w-full sm:w-auto btn-primary-elevated"
              >
                {{ $t("about.learnMore") }}
              </UButton>
              <div class="flex flex-wrap items-center gap-4 px-2 sm:px-4 py-2">
                <div class="flex -space-x-4">
                  <img
                    alt="User"
                    class="w-10 h-10 rounded-full border-2 border-white"
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuCtRfJfLRZ1KtdiyI3wEc5yZAv5PIogJqbPQ5N4_sqSM4nWbOO-p7fGbJ-eY5Zvnm-oQ1CEmHvxkvkzdVtgKKAnWx44D59UtyvGoLEvxjs1yPhZDSjmVBe-38Bz9uv99BZkcTrY6twigKmcAmHpH4KEL6I_QRgxUxw4CUObsREw2qL9aWP17BasKsH8NVqXVpw3nFw4J0uv9XUF2iahbbUhMzfx5Y2lPhdp89PxQHH0SrCUpGdBFpCsYr_fQkIT9MichFRGuIg9gNA"
                  />
                  <img
                    alt="User"
                    class="w-10 h-10 rounded-full border-2 border-white"
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuBUAXWGj5O7JPqsqmdyMp0wcvlqJY4QDZuU4ezLvvlAmCmLJCpfq0ki24d1Cu6N9wDX9I5IE-Vu6WqPmuHCJfm2punsaODN5kP1Y5RKLmmU8tw3Yc3CUs-gKTXQ5f9GUaAnj0-9xRwFZmSc68G1M_d-Jrhribj8Npn_WZeVTt8kxr-ALFt-1xgPQtvyKttYV-480Mk6P2p8-a3OdYsCFfT3BgXHJueY10C2pho5Nt3U-Bc8BibR7bkzrxmIiYZrQ-EBwN6pyz__w5E"
                  />
                  <img
                    alt="User"
                    class="w-10 h-10 rounded-full border-2 border-white"
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuBr9zEGg0hz9OBsBr5iRGQAmVFOxoZBu5wrrw0irWCDKJrlaTHMelMPnx7MytRdWT3dGk8gVCJhLpF9GH-Cv_AFx2bFltEdEAvHQtQxC0Lqkms1_e0HJABJtMdO7ooFhgID5qMl4xrPQL6lBrst8XbG47FwKYfZoaVz59e36oZnc4cjvY6jHFJtYTxEKlvGqDqhxfLQXxiASRhhZq0nzk_AhuOYqDt6Ii4Z4hECPxQDDd8Mhlp4zcbyRhCYS2uuAcGIs-B0wNCRMhE"
                  />
                </div>
                <span
                  class="text-sm font-medium text-slate-600 dark:text-slate-300"
                  >{{ $t("about.experts") }}</span
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </UContainer>
  </section>
</template>

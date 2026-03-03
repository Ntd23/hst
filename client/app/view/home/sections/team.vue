<template>
  <section class="py-16 bg-slate-50/50 dark:bg-slate-900/50">
    <UContainer>
      <div
        v-motion
        :initial="{ opacity: 0, y: 30 }"
        :visible-once="{ opacity: 1, y: 0, transition: { duration: 600 } }"
        class="text-center mb-10 sm:mb-16"
      >
        <span class="text-primary font-semibold tracking-wide uppercase text-sm">{{ $t('team.subtitle') }}</span>
        <h2 class="mt-2 text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">
          {{ $t('team.title') }}
        </h2>
      </div>

      <!-- Photo Cards: phone=1col, tablet/desktop=side by side -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 sm:gap-8 max-w-4xl mx-auto">
        <div
          v-for="(member, i) in team"
          :key="member.name"
          v-motion
          :initial="{ opacity: 0, x: i % 2 === 0 ? -40 : 40 }"
          :visible-once="{ opacity: 1, x: 0, transition: { duration: 600, delay: i * 100 } }"
          class="group"
        >
          <div class="card-hover-glow rounded-2xl overflow-hidden shadow-lg transition-all duration-400 bg-white dark:bg-slate-800">
            <!-- Photo -->
            <div class="h-52 sm:h-56 md:h-64 lg:h-72 overflow-hidden relative">
              <NuxtImg
                :alt="member.name"
                :src="member.image"
                format="webp"
                loading="lazy"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
              />
              <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent" />
              <div class="absolute bottom-3 sm:bottom-4 left-4 sm:left-5 right-4 sm:right-5">
                <h3 class="text-lg sm:text-xl font-bold text-white">{{ member.name }}</h3>
                <p class="text-primary-300 text-xs sm:text-sm font-medium">{{ member.role }}</p>
              </div>
            </div>

            <!-- Info -->
            <div class="p-4 sm:p-5">
              <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed mb-3 sm:mb-4 line-clamp-3">
                {{ member.bio }}
              </p>
              <div class="flex items-center justify-between">
                <ULink class="text-sm font-semibold text-primary hover:text-secondary transition-colors" to="#">
                  Liên hệ với tôi
                </ULink>
                <div class="flex gap-2">
                  <button
                    v-for="social in member.socials"
                    :key="social.icon"
                    class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-500 hover:bg-primary hover:text-white transition-all duration-200"
                  >
                    <UIcon :name="social.icon" class="size-4" />
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-10 sm:mt-12 text-center">
        <UButton
          color="primary"
          variant="solid"
          trailing-icon="i-lucide-arrow-right"
          class="w-full sm:w-auto px-6 sm:px-8 py-3 rounded-xl font-bold btn-primary-lift"
        >
           {{ $t('team.contactUs') }}
        </UButton>
      </div>
    </UContainer>
  </section>
</template>

<script setup lang="ts">
const { t } = useI18n()
const team = computed(() => [
  {
    name: 'Hà Đại Dương',
    role: t('team.members.duong.role'),
    bio: t('team.members.duong.bio'),
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDGed5sFGvQ8lGyq74w9Fk1Q5ZUTOThkerqc5XTWOu8sSBZs7C4FDojg-qjsKRCPZMQ2SX5Bl1XXX0JEqPugpWOD3Aq5LjsN7CGtjDHg7KKyi87IUE8Z_23cQ64g46RdJXFCmjRRfYqhUn0QROuSsmle1EDSrLH9eDKzdsiI0y8vjwt18X3SJEyQtxP9epwUo5Q-EX8guAtC4BgtuFUX6HN9WU32CXRi5uwvu_XQHq2tBYtOwomf4ghMWhmcMjaMNAK98F7jqnJKcM',
    socials: [
      { icon: 'i-lucide-mail' },
      { icon: 'i-lucide-phone' },
    ],
  },
  {
    name: 'Phùng Quang Huy',
    role: t('team.members.huy.role'),
    bio: t('team.members.huy.bio'),
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuBN2na6raGf_dpLJ5IC5lpY83tEyCKavx26dpl2UG6ck33SzHhvF1DyP6rZTTuTSp-WWmntoFCtnDPLEDFUfY-CJsDYbvpoR9l6aiuFyWMENslHhjTgdnwFlVum8I6tSQhGboWPNVXI2RxPe4ZS0P-wyEC6252lvTyj9sbw0I3UOJw4pI7q3IbTV0yz8d96ykB2l4J73k23Q85I2n8BnUwErqMaNkL22PFibyCpJOt3MAZY4uBmQQlGwzKBatggmdD08rZl5x7rd2g',
    socials: [
      { icon: 'i-lucide-mail' },
      { icon: 'i-lucide-phone' },
    ],
  },
])
</script>

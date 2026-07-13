<template>
  <section class="testimonials-highlight relative py-6 overflow-hidden">
    <!-- Ambient Decor -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden h-full">
      <div class="vacuum-blob blob-1" />
      <div class="vacuum-blob blob-2" />
    </div>

    <UContainer class="relative z-10 px-0">
      <div class="px-4">
        <CommonsSectionHeading
          :title="sectionData.title"
          :subtitle="sectionData.subtitle"
          compact
        />
      </div>

      <!-- Testimonial Highlight Carousel -->
      <div class="relative w-full">
        <!-- Scroll-Snap Slider -->
        <div 
          ref="sliderRef"
          class="testimonials-slider flex gap-4 overflow-x-auto scroll-snap-x mandatory py-3 px-[11%] lg:gap-6 lg:px-[30%] no-scrollbar"
        >
          <div
            v-for="(item, idx) in testimonialItems"
            :key="item.id || idx"
            class="testimonial-slide shrink-0 w-[76vw] sm:w-[340px] lg:w-[380px] scroll-snap-align-center"
            :class="{ 'is-active': activeIndex === idx }"
          >
            <!-- Card Body -->
            <div class="highlight-card h-full rounded-[2.25rem] p-6 sm:p-8 lg:p-10 relative group transition-all duration-700">
              <!-- Rating Stars -->
              <div class="flex gap-1 mb-2 justify-center"> 
                <CommonsBotbleIcon
                  v-for="star in 5" 
                  :key="star" 
                  :icon="star <= getRating(item) ? 'i-heroicons-star-20-solid' : 'i-heroicons-star'"
                  class="size-4 transition-transform duration-500"
                  :class="[star <= getRating(item) ? 'text-amber-400 scale-110' : 'text-slate-200']"
                />
              </div>

              <!-- Main Content Area -->
              <div class="relative">
                <!-- Cleaner Quote Icon (Behind text) -->
                <div class="absolute -top-6 -left-3 text-slate-400 opacity-[0.05] pointer-events-none select-none z-0 group-hover:opacity-[0.1] transition-opacity duration-700">
                  <svg width="92" height="92" viewBox="0 0 24 24" fill="currentColor"><path d="M14.017 21L14.017 18C14.017 16.8954 14.9124 16 16.017 16H19.017C19.5693 16 20.017 15.5523 20.017 15V9C20.017 8.44772 19.5693 8 19.017 8H16.017C15.4647 8 15.017 8.44772 15.017 9V12C15.017 12.5523 14.5693 13 14.017 13H12.017V21H14.017ZM5.01704 21L5.01704 18C5.01704 16.8954 5.91242 16 7.01704 16H10.017C10.5693 16 11.017 15.5523 11.017 15V9C11.017 8.44772 10.5693 8 10.017 8H7.01704C6.46476 8 6.01704 8.44772 6.01704 9V12C6.01704 12.5523 5.56933 13 5.01704 13H3.01704V21H5.01704Z"></path></svg>
                </div>
                <div
                  class="testimonial-text text-sm sm:text-[15px] lg:text-base font-medium text-slate-800 leading-7 relative z-10 italic text-center"
                  :title="item.fullContent"
                >
                  {{ item.previewContent }}
                </div>
              </div>

              <!-- Author Glass Badge -->
              <div class="flex flex-col items-center gap-3 text-center mt-auto border-t border-slate-100 pt-6">
                <div class="avatar-perspective relative">
                  <NuxtImg
                    v-if="item.image"
                    :src="item.image"
                  :alt="item.name"
                  class="w-14 h-14 sm:w-16 sm:h-16 rounded-[1rem] object-cover shadow-2xl border-4 border-white transition-all duration-700 active-author-img"
                />
                  <div v-else class="w-14 h-14 sm:w-16 sm:h-16 rounded-[1rem] bg-indigo-50 border-2 border-white flex items-center justify-center">
                    <CommonsBotbleIcon icon="i-lucide-user" class="size-6 text-indigo-300" />
                  </div>
                </div>
                <div>
                  <h4 class="text-base sm:text-lg font-black text-slate-950 leading-tight" v-html="item.name" />
                  <p class="text-[9px] sm:text-[10px] font-black text-indigo-500 uppercase tracking-[0.18em] mt-1.5 opacity-70" v-html="item.company" />
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Slider Controls -->
        <div class="flex justify-center gap-12 mt-4 items-center">
          <div class="flex gap-2.5">
            <button
              v-for="(item, idx) in items"
              :key="'dot-' + idx"
              @click="scrollToIndex(Number(idx))"
              class="h-1.5 rounded-full transition-all duration-500"
              :class="[activeIndex === idx ? 'w-8 bg-indigo-600' : 'w-1.5 bg-slate-200 hover:bg-slate-300']"
            />
          </div>
        </div>
      </div>
    </UContainer>
  </section>
</template>

<script setup lang="ts">
import CommonsBotbleIcon from "~/components/commons/BotbleIcon.vue";
import CommonsSectionHeading from "~/components/commons/SectionHeading.vue";

const props = defineProps<{
  data?: any
}>()

const MAX_TESTIMONIAL_CHARS = 150

const { sectionData, items, sliderRef, activeIndex, scrollToIndex } =
  useTestimonialsShortcode(toRef(props, "data"))

const stripHtml = (value?: string) =>
  (value || "")
    .replace(/<[^>]*>/g, " ")
    .replace(/\s+/g, " ")
    .trim()

const truncateText = (value: string, maxLength = MAX_TESTIMONIAL_CHARS) => {
  if (value.length <= maxLength) {
    return value
  }

  return `${value.slice(0, maxLength).trimEnd()}...`
}

const getRating = (item: any) => {
  const rawRating = Number(item?.rating_star ?? item?.rating ?? 0)

  if (!Number.isFinite(rawRating) || rawRating <= 0) {
    return 5
  }

  return Math.max(0, Math.min(5, Math.round(rawRating)))
}

const testimonialItems = computed(() =>
  items.value.map((item: any) => {
    const fullContent = stripHtml(item.content || item.description || "")

    return {
      ...item,
      fullContent,
      previewContent: truncateText(fullContent),
    }
  })
)
</script>

<style scoped>
.testimonials-highlight {
  font-family: var(--font-tech, 'Space Grotesk', sans-serif);
}

/* ── Vacuum Background ── */
.vacuum-blob {
  position: absolute;
  border-radius: 50%;
  filter: blur(140px);
}
.blob-1 {
  width: 700px; height: 700px;
  top: -100px; left: -100px;
  background: radial-gradient(circle, rgba(99, 102, 241, 0.12), transparent 70%);
}
.blob-2 {
  width: 600px; height: 600px;
  bottom: -100px; right: -100px;
  background: radial-gradient(circle, rgba(14, 165, 233, 0.1), transparent 70%);
}

.highlight-kicker {
  font-family: var(--font-tech, sans-serif);
  display: inline-block;
  padding: 8px 16px;
  background: white;
  border: 1px solid #e2e8f0;
  border-radius: 100px;
  font-size: 0.75rem;
  font-weight: 800;
  letter-spacing: 0.1em;
  color: #475569;
  text-transform: uppercase;
  box-shadow: 0 4px 12px rgba(0,0,0,0.03);
}

/* ── Slider Styles ── */
.testimonials-slider {
  mask-image: linear-gradient(to right, transparent, black 15%, black 85%, transparent);
  -webkit-mask-image: linear-gradient(to right, transparent, black 15%, black 85%, transparent);
}
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

.testimonial-slide {
  transition: all 0.7s cubic-bezier(0.23, 1, 0.32, 1);
  opacity: 0.42;
  transform: scale(0.88);
  filter: blur(3px);
}

.testimonial-slide.is-active {
  opacity: 1;
  transform: scale(1);
  filter: blur(0);
}

.highlight-card {
  background: white;
  border: 1px solid #f1f5f9;
  box-shadow: 0 40px 100px -20px rgba(0,0,0,0.08);
  display: flex;
  flex-direction: column;
  min-height: 24rem;
}

.testimonial-slide.is-active .highlight-card {
  box-shadow: 
    0 40px 100px -20px rgba(0,0,0,0.12),
    0 10px 40px rgba(0,0,0,0.04);
  border-color: #f1f5f9;
}

.active-author-img {
  transform: translateY(0);
}
.testimonial-slide:not(.is-active) .active-author-img {
  transform: translateY(20px);
}

.avatar-perspective {
  perspective: 1000px;
}

@media (max-width: 1024px) {
  .testimonials-slider {
    padding-left: 10%;
    padding-right: 10%;
    mask-image: none;
    -webkit-mask-image: none;
  }

  .highlight-card {
    min-height: 20rem;
  }
}

.testimonial-text {
  min-height: 8.75rem;
  display: -webkit-box;
  -webkit-line-clamp: 5;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

@media (min-width: 640px) {
  .testimonial-text {
    min-height: 9.5rem;
  }
}
</style>

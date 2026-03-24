<template>
  <section 
    class="about-section relative overflow-hidden py-6" 
  >
    <!-- Dynamic Vacuum Ambience -->
    <div class="absolute inset-0 pointer-events-none opacity-40">
      <div class="vacuum-blob blob-1" />
      <div class="vacuum-blob blob-2" />
      <div class="vacuum-particle" style="top: 20%; left: 15%; animation-delay: 1s;" />
      <div class="vacuum-particle" style="top: 70%; left: 80%; animation-delay: 2s;" />
    </div>

    <UContainer class="relative z-10">
      <div 
        class="grid grid-cols-1 items-center gap-12 lg:gap-20"
        :class="[isImageRight ? 'lg:grid-cols-[1.05fr_0.95fr]' : 'lg:grid-cols-[0.95fr_1.05fr]']"
      >
        <!-- CONTENT SIDE -->
        <div
          v-motion
          :initial="{ opacity: 0, x: isImageRight ? -40 : 40 }"
          :visible-once="{ opacity: 1, x: 0, transition: { duration: 800, ease: 'easeOut' } }"
          class="w-full"
          :class="[isImageRight ? 'lg:order-1' : 'lg:order-2']"
        >
          <div v-if="sectionData.subtitle" class="section-kicker mb-6" v-html="sectionData.subtitle" />

          <h2
            v-if="sectionData.title"
            class="font-space text-3xl font-extrabold leading-[1.15] tracking-tight text-slate-900 sm:text-4xl lg:text-5xl xl:text-6xl mb-8"
            v-html="sectionData.title"
          />

          <p
            v-if="sectionData.description"
            class="text-base sm:text-lg leading-relaxed text-slate-500 mb-10 max-w-2xl"
            v-html="sectionData.description"
          />

          <!-- Premium CTA Button -->
          <div v-if="sectionData.button_label" class="mb-12">
            <NuxtLink
              :to="sectionData.button_url || '#'"
              class="about-cta group"
            >
              <span class="about-cta__text">{{ sectionData.button_label }}</span>
              <span class="about-cta__icon">
                <UIcon name="i-lucide-arrow-right" class="size-4 group-hover:translate-x-0.5 transition-transform duration-300" />
              </span>
            </NuxtLink>
          </div>

          <!-- Feature Cards for Left-Aligned Layout -->
          <div v-if="!isImageRight && tabs.length" class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div
              v-for="(tab, idx) in tabs"
              :key="idx"
              class="feature-card"
            >
              <div class="feature-card__icon">
                <UIcon :name="tab.icon || 'i-lucide-check-circle-2'" class="size-6 text-primary" />
              </div>
              <h3 class="font-space mt-4 text-xl font-bold text-slate-900" v-html="tab.title" />
              <p v-if="tab.description" class="mt-2 text-sm text-slate-500 leading-relaxed" v-html="tab.description" />
            </div>
          </div>
        </div>

        <!-- IMAGE SIDE & FLOATING TABS -->
        <div
          v-motion
          :initial="{ opacity: 0, x: isImageRight ? 40 : -40 }"
          :visible-once="{ opacity: 1, x: 0, transition: { duration: 800, ease: 'easeOut' } }"
          class="relative w-full max-w-xl mx-auto lg:mx-0"
          :class="[isImageRight ? 'lg:order-2' : 'lg:order-1']"
        >
          <!-- Main Frame -->
          <div class="hero-frame relative rounded-[3rem] overflow-hidden shadow-2xl aspect-[4/5] group">
            <NuxtImg
              v-if="sectionData.image"
              :alt="sectionData.title || 'About Us'"
              class="w-full h-full object-cover transition-transform duration-[2s] group-hover:scale-110"
              :src="sectionData.image"
            />
            <div class="absolute inset-0 bg-gradient-to-tr from-slate-900/10 to-transparent mix-blend-overlay" />
          </div>

          <!-- Floating Glass Tabs (Always visible if isImageRight, or as an option) -->
          <div 
            v-if="tabs.length && (isImageRight || isFloatingMode)" 
            class="absolute -bottom-4 -left-6 sm:-left-12 flex flex-col gap-4 z-20 pointer-events-none sm:pointer-events-auto"
          >
            <div 
              v-for="(tab, idx) in tabs.slice(0, 3)" 
              :key="idx"
              v-motion
              :initial="{ opacity: 0, y: 30 }"
              :visible-once="{ opacity: 1, y: 0, transition: { delay: 400 + (idx * 150) } }"
              class="floating-node flex items-center gap-4 p-4 pr-7 rounded-2xl animate-float"
              :style="{ animationDelay: `${idx * 0.8}s` }"
            >
              <div class="node-icon shrink-0 w-11 h-11 rounded-xl bg-white flex items-center justify-center shadow-lg shadow-primary/10">
                <UIcon :name="tab.icon || 'i-lucide-zap'" class="size-5 text-primary" />
              </div>
              <div class="flex flex-col">
                <span class="text-sm font-black text-slate-900 whitespace-nowrap leading-tight" v-html="tab.title" />
                <span v-if="tab.description" class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5 line-clamp-1">Service</span>
              </div>
            </div>
          </div>

          <!-- Decorative Glow Particles -->
          <div class="absolute -top-12 -right-12 w-64 h-64 bg-primary/15 rounded-full blur-3xl pointer-events-none -z-10" />
        </div>
      </div>
    </UContainer>
  </section>
</template>

<script setup lang="ts">
const props = defineProps<{
  data?: any
}>()

const sectionData = computed(() => {
  const d = props.data?.data || props.data || {};
  return {
    ...props.data, 
    ...d          
  }
})

// Logic for alternating/generic layout
const isImageRight = computed(() => {
  const style = sectionData.value.style || '';
  // Support style-14, style-8, or any style containing "right"
  return style.includes('14') || style.includes('right') || style.includes('style-8');
})

const isFloatingMode = computed(() => isImageRight.value || tabs.value.length <= 3);

const tabs = computed(() => sectionData.value?.tabs || [])
</script>

<style scoped>
.about-section {
  font-family: 'Inter', sans-serif;
}

.font-space {
  font-family: 'Space Grotesk', 'Inter', sans-serif;
}

/* ── Vacuum Elements ── */
.vacuum-blob {
  position: absolute;
  border-radius: 50%;
  filter: blur(120px);
}
.blob-1 {
  width: 600px; height: 600px;
  top: -10%; left: -10%;
  background: radial-gradient(circle, rgba(14, 165, 233, 0.1), transparent 70%);
}
.blob-2 {
  width: 500px; height: 500px;
  bottom: -5%; right: -5%;
  background: radial-gradient(circle, rgba(99, 102, 241, 0.08), transparent 70%);
}
.vacuum-particle {
  position: absolute;
  width: 3px; height: 3px;
  background: #cbd5e1;
  border-radius: 50%;
  animation: twinkle 5s linear infinite;
}

/* ── Section Kicker (Badge) ── */
.section-kicker {
  display: inline-flex;
  padding: 0.5rem 1.1rem;
  background: rgba(14, 165, 233, 0.08);
  border: 1px solid rgba(14, 165, 233, 0.12);
  border-radius: 999px;
  color: #0284c7;
  font-size: 0.75rem;
  font-weight: 800;
  letter-spacing: 0.15em;
  text-transform: uppercase;
  backdrop-filter: blur(8px);
}

/* ── Hero Frame ── */
.hero-frame {
  background: linear-gradient(135deg, #f8fafc, #eff6ff);
  box-shadow: 
    0 50px 100px -20px rgba(0,0,0,0.1),
    inset 0 1px 0 rgba(255,255,255,1);
}

/* ── Premium CTA (Vibrant Glass) ── */
.about-cta {
  display: inline-flex;
  align-items: center;
  gap: 0.875rem;
  padding: 0.625rem 1.6rem;
  border-radius: 999px;
  background: linear-gradient(135deg, #d1fae5, #dbeafe);
  border: 1px solid #a7f3d0;
  color: #059669;
  font-weight: 700;
  font-size: 0.95rem;
  text-decoration: none;
  transition: all 0.35s cubic-bezier(0.23, 1, 0.32, 1);
  box-shadow: 0 4px 12px rgba(16, 185, 129, 0.1);
}

.about-cta:hover {
  background: linear-gradient(135deg, #10b981, #3b82f6);
  color: white;
  border-color: transparent;
  transform: translateY(-2px);
  box-shadow: 0 12px 24px rgba(16, 185, 129, 0.35);
}

.about-cta__icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 1.75rem;
  height: 1.75rem;
  border-radius: 999px;
  background: rgba(255, 255, 255, 0.35);
  border: 1px solid rgba(255, 255, 255, 0.5);
  transition: transform 0.35s ease;
}

.about-cta:hover .about-cta__icon {
  background: rgba(255, 255, 255, 0.2);
  border-color: rgba(255, 255, 255, 0.3);
  transform: translateX(2px);
}

/* ── Floating Nodes (Glass) ── */
.floating-node {
  background: rgba(255, 255, 255, 0.85);
  backdrop-filter: blur(20px);
  border: 1.5px solid white;
  box-shadow: 
    0 30px 60px -12px rgba(0,0,0,0.08),
    inset 0 1px 0 white;
}
.node-icon {
  background: linear-gradient(135deg, rgba(14, 165, 233, 0.1), rgba(99, 102, 241, 0.05));
}

/* ── Feature Cards (Default Look) ── */
.feature-card {
  padding: 1.5rem;
  border-radius: 1.5rem;
  background: white;
  border: 1px solid rgba(226, 232, 240, 0.8);
  box-shadow: 0 10px 20px rgba(0,0,0,0.02);
  transition: all 0.4s ease;
}
.feature-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 15px 30px rgba(0,0,0,0.05);
  border-color: rgba(14, 165, 233, 0.3);
}
.feature-card__icon {
  width: 3.5rem; height: 3.5rem;
  border-radius: 1rem;
  background: rgba(14, 165, 233, 0.05);
  display: flex; align-items: center; justify-content: center;
}

@keyframes float {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-12px); }
}
.animate-float {
  animation: float 5s ease-in-out infinite;
}

@keyframes twinkle {
  0%, 100% { opacity: 0.3; transform: scale(0.8); }
  50% { opacity: 0.8; transform: scale(1.1); }
}
</style>

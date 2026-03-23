<template>
  <section class="py-10 sm:py-14">
    <UContainer>
      <div
        v-motion
        :initial="{ opacity: 0, y: 30, scale: 0.97 }"
        :visible-once="{ opacity: 1, y: 0, scale: 1, transition: { duration: 700, ease: [0.16, 1, 0.3, 1] } }"
        class="cta-card relative overflow-hidden"
      >
        <!-- Background image (if provided) -->
        <NuxtImg
          v-if="sectionData.background_image"
          :src="sectionData.background_image"
          alt=""
          class="absolute inset-0 w-full h-full object-cover opacity-20"
        />

        <!-- Gradient mesh blobs -->
        <div class="blob blob-1" />
        <div class="blob blob-2" />
        <div class="blob blob-3" />

        <!-- Glass content panel -->
        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-8 sm:gap-10 items-center p-6 sm:p-10 lg:p-14">

          <!-- Text side -->
          <div class="space-y-5 sm:space-y-6">
            <h2
              v-if="sectionData.title"
              class="cta-title text-xl sm:text-2xl lg:text-3xl font-extrabold leading-snug tracking-tight"
              v-html="sectionData.title"
            />
            <p
              v-if="sectionData.subtitle"
              class="text-white/65 text-sm sm:text-base leading-relaxed max-w-md"
              v-html="sectionData.subtitle"
            />
            <div v-if="sectionData.button_label">
              <NuxtLink
                :to="sectionData.button_url || '#'"
                class="cta-btn group/btn"
              >
                <span class="cta-btn-text">{{ sectionData.button_label }}</span>
                <span class="cta-btn-icon">
                  <UIcon name="i-lucide-arrow-right" class="size-4 group-hover/btn:translate-x-0.5 transition-transform" />
                </span>
              </NuxtLink>
            </div>
          </div>

          <!-- 3D Glassmorphism decorative side -->
          <div class="hidden lg:flex items-center justify-center">
            <div class="deco-scene">
              <!-- Outer glass ring -->
              <div class="glass-ring glass-ring-outer">
                <div class="glass-ring glass-ring-inner">
                  <!-- Core sphere -->
                  <div class="glass-sphere">
                    <UIcon name="i-lucide-zap" class="size-7 text-white drop-shadow-lg" />
                  </div>
                </div>
              </div>
              <!-- Floating glass chips -->
              <div class="glass-chip chip-1" />
              <div class="glass-chip chip-2" />
              <div class="glass-chip chip-3" />
            </div>
          </div>
        </div>
      </div>
    </UContainer>
  </section>
</template>

<script setup lang="ts">
const props = defineProps<{
  data?: any
}>()

const sectionData = computed(() => props.data?.data || props.data || {})
</script>

<style scoped>
/* ── CTA Card: Vibrant gradient base ── */
.cta-card {
  border-radius: 2rem;
  min-height: 220px;
  background: linear-gradient(135deg, #0c2d57 0%, #0c4a6e 50%, #312e81 100%);
  box-shadow:
    0 24px 60px -12px rgba(12, 45, 87, 0.5),
    0 0 0 1px rgba(255,255,255,0.06);
}

/* ── Gradient mesh blobs for depth ── */
.blob {
  position: absolute;
  border-radius: 50%;
  filter: blur(70px);
  pointer-events: none;
}
.blob-1 {
  width: 320px; height: 320px;
  top: -60px; right: -40px;
  background: radial-gradient(circle, rgba(34,211,238,0.4) 0%, transparent 70%);
}
.blob-2 {
  width: 280px; height: 280px;
  bottom: -80px; left: -20px;
  background: radial-gradient(circle, rgba(99,102,241,0.35) 0%, transparent 70%);
}
.blob-3 {
  width: 200px; height: 200px;
  top: 50%; left: 40%;
  transform: translate(-50%, -50%);
  background: radial-gradient(circle, rgba(56,189,248,0.2) 0%, transparent 70%);
}

/* ── Title: gradient text cyan → green ── */
.cta-title {
  font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
  background: linear-gradient(135deg, #ffffff 0%, #a5f3fc 40%, #c7d2fe 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/* ── CTA Button: sleek premium glass pill ── */
.cta-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.625rem;
  padding: 0.7rem 1.5rem 0.7rem 1.75rem;
  border-radius: 999px;
  background: rgba(255,255,255,0.08);
  backdrop-filter: blur(12px);
  border: 1px solid rgba(255,255,255,0.18);
  color: white;
  font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
  font-weight: 600;
  font-size: 0.875rem;
  letter-spacing: 0.01em;
  text-decoration: none;
  transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
  box-shadow: 0 0 20px rgba(34,211,238,0.1);
}
.cta-btn:hover {
  background: rgba(255,255,255,0.14);
  border-color: rgba(165,243,252,0.4);
  box-shadow: 0 0 30px rgba(34,211,238,0.25), 0 0 60px rgba(99,102,241,0.1);
  transform: translateY(-2px);
}
.cta-btn-text {
  background: linear-gradient(90deg, #e0f2fe, #ffffff);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
.cta-btn:hover .cta-btn-text {
  background: linear-gradient(90deg, #a5f3fc, #ffffff);
  -webkit-background-clip: text;
  background-clip: text;
}
.cta-btn-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 28px; height: 28px;
  border-radius: 50%;
  background: rgba(255,255,255,0.1);
  border: 1px solid rgba(255,255,255,0.12);
}

/* ── 3D Glassmorphism decorative elements ── */
.deco-scene {
  position: relative;
  width: 200px;
  height: 200px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.glass-ring {
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  border: 1.5px solid rgba(255,255,255,0.12);
  backdrop-filter: blur(4px);
}
.glass-ring-outer {
  width: 180px; height: 180px;
  background: rgba(255,255,255,0.04);
  animation: float-slow 6s ease-in-out infinite;
}
.glass-ring-inner {
  width: 120px; height: 120px;
  background: rgba(255,255,255,0.06);
  border-color: rgba(165,180,252,0.25);
  animation: float-slow 4s ease-in-out infinite reverse;
}

.glass-sphere {
  width: 64px; height: 64px;
  border-radius: 50%;
  background: linear-gradient(145deg, rgba(34,211,238,0.35), rgba(129,140,248,0.3));
  backdrop-filter: blur(12px);
  border: 1px solid rgba(255,255,255,0.2);
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow:
    0 0 40px rgba(34,211,238,0.25),
    inset 0 1px 0 rgba(255,255,255,0.3),
    inset 0 -2px 6px rgba(0,0,0,0.15);
}

/* Floating glass chips */
.glass-chip {
  position: absolute;
  border-radius: 0.75rem;
  background: rgba(255,255,255,0.08);
  backdrop-filter: blur(8px);
  border: 1px solid rgba(255,255,255,0.12);
}
.chip-1 {
  width: 40px; height: 40px;
  top: 10px; right: 20px;
  animation: float-chip 5s ease-in-out infinite;
  border-radius: 50%;
}
.chip-2 {
  width: 28px; height: 28px;
  bottom: 20px; left: 15px;
  animation: float-chip 6s ease-in-out infinite 1s;
}
.chip-3 {
  width: 20px; height: 20px;
  top: 40%; right: 5px;
  animation: float-chip 4s ease-in-out infinite 2s;
  border-radius: 50%;
}

@keyframes float-slow {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-8px); }
}
@keyframes float-chip {
  0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0.7; }
  50% { transform: translateY(-12px) rotate(8deg); opacity: 1; }
}
</style>

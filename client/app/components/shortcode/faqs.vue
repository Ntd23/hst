<template>
  <section class="faq-vacuum relative py-20 lg:py-32 overflow-hidden">
    <!-- Ambient Vacuum Background Elements -->
    <div class="absolute inset-0 pointer-events-none opacity-40">
      <div class="vacuum-blob blob-1" />
      <div class="vacuum-blob blob-2" />
      <div class="vacuum-particle p-1" style="top: 15%; left: 10%;" />
      <div class="vacuum-particle p-2" style="top: 65%; left: 45%;" />
      <div class="vacuum-particle p-3" style="top: 30%; left: 85%;" />
    </div>

    <UContainer class="relative z-10">
      <!-- Header Section -->
      <div 
        v-motion
        :initial="{ opacity: 0, y: 20 }"
        :visible-once="{ opacity: 1, y: 0, transition: { duration: 800, ease: 'easeOut' } }"
        class="text-center mb-16 lg:mb-24"
      >
        <h2 
          v-if="sectionData.title" 
          class="text-4xl lg:text-6xl font-extrabold tracking-tight text-slate-900 mb-6"
          v-html="sectionData.title"
        />
        <p 
          v-if="sectionData.description" 
          class="max-w-3xl mx-auto text-lg text-slate-500 leading-relaxed"
          v-html="sectionData.description"
        />
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
        <!-- LEFT: Floating Question Nodes -->
        <div class="space-y-6">
          <div
            v-for="(item, idx) in faqs"
            :key="item.id || item.question"
            v-motion
            :initial="{ opacity: 0, x: -30 }"
            :visible-once="{ opacity: 1, x: 0, transition: { duration: 600, delay: Number(idx) * 100 } }"
            class="question-node group"
            :class="{ 'is-active': activeFaq?.id === item.id }"
            @mouseenter="activeFaq = item"
            @click="activeFaq = item"
          >
            <div class="node-glass flex items-center gap-5 p-6 rounded-3xl transition-all duration-500">
              <div class="node-index shrink-0 flex items-center justify-center w-12 h-12 rounded-2xl bg-white border border-slate-100 shadow-sm group-hover:scale-110 transition-transform duration-500">
                <span class="text-lg font-bold text-slate-400 group-hover:text-primary transition-colors">0{{ Number(idx) + 1 }}</span>
              </div>
              <h3 
                class="text-lg font-bold text-slate-700 leading-snug group-hover:text-slate-900 transition-colors"
                v-html="item.question"
              />
              <div class="node-arrow ml-auto opacity-0 group-hover:opacity-100 translate-x-4 group-hover:translate-x-0 transition-all duration-500 text-primary">
                <UIcon name="i-lucide-arrow-right" class="size-6" />
              </div>
            </div>
          </div>
        </div>

        <!-- RIGHT: Dynamic Answer Vacuum -->
        <div class="relative min-h-[400px] flex items-center justify-center">
          <Transition name="fade-slide" mode="out-in">
            <div 
              v-if="activeFaq" 
              :key="activeFaq.id"
              class="answer-portal relative w-full aspect-square max-w-[500px]"
            >
              <!-- Glass Core -->
              <div class="portal-glass absolute inset-0 rounded-[3rem] p-8 lg:p-12 flex flex-col justify-center">
                <div class="mb-4 flex items-center gap-3">
                  <span class="px-3 py-1.5 rounded-full bg-primary/10 text-primary text-[10px] font-black uppercase tracking-widest leading-none border border-primary/5">Expert Answer</span>
                  <div class="h-px flex-1 bg-gradient-to-r from-primary/25 to-transparent shadow-[0_0_10px_rgba(56,189,248,0.2)]" />
                </div>
                
                <div class="answer-content text-slate-600 leading-[1.8] text-lg lg:text-xl font-medium" v-html="activeFaq.answer" />

                <!-- Floating 3D Support Element (Satellite style, moved out of text path) -->
                <div 
                  v-if="sectionData.floating_block"
                  class="absolute -top-12 lg:-top-20 -right-6 lg:-right-12 w-36 h-36 bg-white/60 backdrop-blur-2xl shadow-2xl rounded-3xl p-5 border border-white/80 hidden sm:flex flex-col justify-center animate-float-spin z-20"
                >
                  <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-primary to-indigo-600 text-white flex items-center justify-center mb-3 shadow-lg shadow-primary/20">
                    <i :class="(sectionData.floating_block.icon || 'ti ti-24-hours') + ' text-lg text-white'" />
                  </div>
                  <h4 class="text-[10px] font-black text-slate-900 uppercase tracking-tighter mb-1" v-html="sectionData.floating_block.title" />
                  <p class="text-[9px] text-slate-500 leading-tight opacity-70 line-clamp-2" v-html="sectionData.floating_block.description" />
                </div>
              </div>

              <!-- Orbiting decorations -->
              <div class="absolute inset-0 pointer-events-none">
                <div class="orbit orbit-1" />
                <div class="orbit orbit-2" />
                <div class="satellite sat-1" />
                <div class="satellite sat-2" />
              </div>
            </div>

            <!-- Fallback Image Scene (If no question hovered or first load) -->
            <div v-else class="support-scene relative w-full aspect-square max-w-[500px]">
               <div class="scene-frame rounded-[3rem] overflow-hidden shadow-2xl">
                 <NuxtImg 
                    v-if="sectionData.image" 
                    :src="sectionData.image" 
                    class="w-full h-full object-cover grayscale-[0.2]"
                 />
                 <div v-else class="w-full h-full bg-slate-900 flex items-center justify-center">
                    <UIcon name="i-lucide-help-circle" class="size-20 text-slate-800" />
                 </div>
                 <div class="absolute inset-0 bg-gradient-to-tr from-slate-900/40 to-transparent" />
               </div>
               
               <!-- Floating support info -->
               <div v-if="sectionData.floating_block" class="absolute -bottom-10 -left-6 bg-white/80 backdrop-blur-xl p-8 rounded-[2rem] border border-white shadow-2xl max-w-xs animate-float">
                  <h3 class="font-black text-xl text-slate-900 mb-2" v-html="sectionData.floating_block.title" />
                  <p class="text-sm text-slate-500 opacity-80" v-html="sectionData.floating_block.description" />
               </div>
            </div>
          </Transition>
        </div>
      </div>
    </UContainer>
  </section>
</template>

<script setup lang="ts">
const props = defineProps<{
  data?: any
}>()

// Support both direct access and content-wrapped access
const rootData = computed(() => props.data?.content || props.data || {})
const sectionData = computed(() => rootData.value || {})
const faqs = computed(() => sectionData.value?.items || [])

const activeFaq = ref<any>(null)

// Initialize with the first FAQ if available
onMounted(() => {
  if (faqs.value.length > 0) {
    activeFaq.value = faqs.value[0]
  }
})
</script>

<style scoped>
.faq-vacuum {
  background: transparent;
  font-family: var(--font-body, 'Inter', sans-serif);
}
.faq-vacuum h2,
.faq-vacuum h3,
.faq-vacuum h4,
.node-index {
  font-family: var(--font-tech, sans-serif);
}

/* ── Vacuum Ambience ── */
.vacuum-blob {
  position: absolute;
  border-radius: 50%;
  filter: blur(100px);
}
.blob-1 {
  width: 500px; height: 500px;
  top: -10%; left: -10%;
  background: radial-gradient(circle, rgba(56,189,248,0.15), transparent 70%);
}
.blob-2 {
  width: 400px; height: 400px;
  bottom: -5%; right: -5%;
  background: radial-gradient(circle, rgba(99,102,241,0.12), transparent 70%);
}
.vacuum-particle {
  position: absolute;
  width: 4px; height: 4px;
  background: #cbd5e1;
  border-radius: 50%;
  box-shadow: 0 0 10px rgba(203, 213, 225, 0.5);
  animation: twinkle 4s ease-in-out infinite;
}

/* ── Question Nodes ── */
.question-node {
  cursor: pointer;
}
.node-glass {
  background: rgba(255, 255, 255, 0.4);
  border: 1px solid rgba(255, 255, 255, 0.6);
  backdrop-filter: blur(8px);
  box-shadow: 0 10px 30px rgba(0,0,0,0.02);
}
.question-node:hover .node-glass,
.question-node.is-active .node-glass {
  background: white;
  border-color: rgba(56, 189, 248, 0.2);
  transform: translateX(12px) scale(1.02);
  box-shadow: 0 20px 40px rgba(56, 189, 248, 0.08);
}
.question-node.is-active .node-index {
  background: #0ea5e9;
  border-color: #0ea5e9;
}
.question-node.is-active .node-index span {
  color: white;
}

/* ── Answer Portal (The Vacuum Reveal) ── */
.answer-portal {
  perspective: 1200px;
}
.portal-glass {
  background: rgba(255, 255, 255, 0.7);
  backdrop-filter: blur(32px);
  border: 1px solid rgba(255, 255, 255, 0.9);
  box-shadow: 
    0 40px 100px -20px rgba(0,0,0,0.05),
    inset 0 1px 0 white;
  z-index: 5;
}

/* ── Orbiting Decor ── */
.orbit {
  position: absolute;
  top: 50%; left: 50%;
  border-radius: 50%;
  border: 1.5px solid rgba(56, 189, 248, 0.1);
  transform: translate(-50%, -50%);
}
.orbit-1 { width: 110%; height: 110%; animation: rotate 30s linear infinite; }
.orbit-2 { width: 130%; height: 130%; animation: rotate 45s linear infinite reverse; }

.satellite {
  position: absolute;
  width: 12px; height: 12px;
  border-radius: 50%;
  background: white;
  box-shadow: 0 0 15px rgba(56, 189, 248, 0.5);
  border: 2px solid #0ea5e9;
}
.sat-1 { top: -6px; left: 50%; }
.sat-2 { bottom: 50%; right: -6px; }

/* ── Support Scene Fallback ── */
.scene-frame {
  width: 100%; height: 100%;
}

/* ── Animations ── */
@keyframes float {
  0%, 100% { transform: translateY(0) rotate(0); }
  50% { transform: translateY(-15px) rotate(2deg); }
}
.animate-float {
  animation: float 6s ease-in-out infinite;
}

@keyframes float-spin {
  0%, 100% { transform: translateY(0) rotate(2deg) scale(1); }
  50% { transform: translateY(-25px) rotate(-1deg) scale(1.05); }
}
.animate-float-spin {
  animation: float-spin 8s ease-in-out infinite;
}

@keyframes twinkle {
  0%, 100% { opacity: 0.3; transform: scale(0.8); }
  50% { opacity: 1; transform: scale(1.2); }
}

@keyframes rotate {
  from { transform: translate(-50%, -50%) rotate(0deg); }
  to { transform: translate(-50%, -50%) rotate(360deg); }
}

.fade-slide-enter-active,
.fade-slide-leave-active {
  transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}
.fade-slide-enter-from {
  opacity: 0;
  transform: translateX(40px) scale(0.95);
}
.fade-slide-leave-to {
  opacity: 0;
  transform: translateX(-40px) scale(0.95);
}

.answer-content :deep(p:first-of-type)::first-letter {
  float: left;
  font-size: 3.5rem;
  line-height: 1;
  font-weight: 900;
  margin-right: 0.75rem;
  margin-top: 0.25rem;
  background: linear-gradient(135deg, #0ea5e9, #6366f1);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
.answer-content :deep(p) {
  margin-bottom: 1.25rem;
}
.answer-content :deep(span) {
  color: inherit !important;
  font-family: inherit !important;
  font-size: inherit !important;
}
</style>

<template>
  <section class="faq-vacuum relative overflow-hidden py-20 lg:py-32">
    <div class="pointer-events-none absolute inset-0 opacity-40">
      <div class="vacuum-blob blob-1" />
      <div class="vacuum-blob blob-2" />
      <div class="vacuum-particle p-1" style="top: 15%; left: 10%;" />
      <div class="vacuum-particle p-2" style="top: 65%; left: 45%;" />
      <div class="vacuum-particle p-3" style="top: 30%; left: 85%;" />
    </div>

    <UContainer class="relative z-10">
      <div
        v-motion
        :initial="{ opacity: 0, y: 20 }"
        :visible-once="{ opacity: 1, y: 0, transition: { duration: 800, ease: 'easeOut' } }"
        class="mb-16 text-center lg:mb-24"
      >
        <h2
          v-if="sectionData.title"
          class="mb-6 text-4xl font-extrabold tracking-tight text-slate-900 lg:text-6xl"
          v-html="sectionData.title"
        />
        <p
          v-if="sectionData.description"
          class="mx-auto max-w-3xl text-lg leading-relaxed text-slate-500"
          v-html="sectionData.description"
        />
      </div>

      <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-2 lg:gap-20">
        <div class="space-y-6">
          <div
            v-for="(item, idx) in faqs"
            :key="item.id || item.question"
            v-motion
            :initial="{ opacity: 0, x: -30 }"
            :visible-once="{ opacity: 1, x: 0, transition: { duration: 600, delay: Number(idx) * 100 } }"
            class="question-node group"
            :class="{ 'is-active': activeFaq?.id === item.id }"
            @mouseenter="setActiveFaq(item)"
            @click="setActiveFaq(item)"
          >
            <div class="node-glass flex items-center gap-5 rounded-3xl p-6 transition-all duration-500">
              <div class="node-index flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-slate-100 bg-white shadow-sm transition-transform duration-500 group-hover:scale-110">
                <span class="text-lg font-bold text-slate-400 transition-colors group-hover:text-primary">0{{ Number(idx) + 1 }}</span>
              </div>
              <h3
                class="text-lg font-bold leading-snug text-slate-700 transition-colors group-hover:text-slate-900"
                v-html="item.question"
              />
              <div class="node-arrow ml-auto translate-x-4 text-primary opacity-0 transition-all duration-500 group-hover:translate-x-0 group-hover:opacity-100">
                <UIcon name="i-lucide-arrow-right" class="size-6" />
              </div>
            </div>
          </div>
        </div>

        <div class="relative flex min-h-[400px] items-center justify-center">
          <Transition name="fade-slide" mode="out-in">
            <div
              v-if="activeFaq"
              :key="activeFaq.id"
              class="answer-portal relative aspect-square w-full max-w-[500px]"
            >
              <div class="portal-glass absolute inset-0 z-[5] flex flex-col justify-center rounded-[3rem] p-8 lg:p-12">
                <div class="mb-4 flex items-center gap-3">
                  <span class="leading-none px-3 py-1.5 rounded-full border border-primary/5 bg-primary/10 text-[10px] font-black uppercase tracking-widest text-primary">Expert Answer</span>
                  <div class="h-px flex-1 bg-gradient-to-r from-primary/25 to-transparent shadow-[0_0_10px_rgba(56,189,248,0.2)]" />
                </div>

                <div class="answer-content text-lg font-medium leading-[1.8] text-slate-600 lg:text-xl" v-html="activeFaq.answer" />

                <div
                  v-if="sectionData.floating_block"
                  class="absolute -right-6 -top-12 z-20 hidden h-36 w-36 flex-col justify-center rounded-3xl border border-white/80 bg-white/60 p-5 shadow-2xl backdrop-blur-2xl animate-float-spin sm:flex lg:-right-12 lg:-top-20"
                >
                  <div class="mb-3 flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-primary to-indigo-600 text-white shadow-lg shadow-primary/20">
                    <i :class="(sectionData.floating_block.icon || 'ti ti-24-hours') + ' text-lg text-white'" />
                  </div>
                  <h4 class="mb-1 text-[10px] font-black uppercase tracking-tighter text-slate-900" v-html="sectionData.floating_block.title" />
                  <p class="line-clamp-2 text-[9px] leading-tight text-slate-500 opacity-70" v-html="sectionData.floating_block.description" />
                </div>
              </div>

              <div class="pointer-events-none absolute inset-0">
                <div class="orbit orbit-1" />
                <div class="orbit orbit-2" />
                <div class="satellite sat-1" />
                <div class="satellite sat-2" />
              </div>
            </div>

            <div v-else class="support-scene relative aspect-square w-full max-w-[500px]">
              <div class="scene-frame rounded-[3rem] overflow-hidden shadow-2xl">
                <NuxtImg
                  v-if="sectionData.image"
                  :src="sectionData.image"
                  class="h-full w-full object-cover grayscale-[0.2]"
                />
                <div v-else class="flex h-full w-full items-center justify-center bg-slate-900">
                  <UIcon name="i-lucide-help-circle" class="size-20 text-slate-800" />
                </div>
                <div class="absolute inset-0 bg-gradient-to-tr from-slate-900/40 to-transparent" />
              </div>

              <div v-if="sectionData.floating_block" class="animate-float absolute -bottom-10 -left-6 max-w-xs rounded-[2rem] border border-white bg-white/80 p-8 shadow-2xl backdrop-blur-xl">
                <h3 class="mb-2 text-xl font-black text-slate-900" v-html="sectionData.floating_block.title" />
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
  data?: any;
}>();

const { siteUrl, canonicalUrl } = useSeoContext();
const { sectionData, faqs, activeFaq, setActiveFaq } = useFaqsShortcode(
  toRef(props, "data")
);

const stripHtml = (value?: string | null) => {
  if (!value) {
    return "";
  }

  return value.replace(/<[^>]*>/g, " ").replace(/\s+/g, " ").trim();
};

const toAbsoluteUrl = (value?: string) => {
  if (!value) {
    return undefined;
  }

  if (/^https?:\/\//i.test(value)) {
    return value;
  }

  return `${siteUrl.value}${value.startsWith("/") ? value : `/${value}`}`;
};

const faqSchema = computed(() => {
  if (!faqs.value.length) {
    return null;
  }

  return {
    "@context": "https://schema.org",
    "@type": "FAQPage",
    url: canonicalUrl.value,
    image: toAbsoluteUrl(sectionData.value?.image),
    mainEntity: faqs.value
      .filter((item: any) => item?.question && item?.answer)
      .map((item: any) => ({
        "@type": "Question",
        name: stripHtml(item.question),
        acceptedAnswer: {
          "@type": "Answer",
          text: stripHtml(item.answer),
        },
      })),
  };
});

useJsonLd(faqSchema);
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
.vacuum-blob {
  position: absolute;
  border-radius: 50%;
  filter: blur(100px);
}
.blob-1 {
  width: 500px;
  height: 500px;
  top: -10%;
  left: -10%;
  background: radial-gradient(circle, rgba(56,189,248,0.15), transparent 70%);
}
.blob-2 {
  width: 400px;
  height: 400px;
  bottom: -5%;
  right: -5%;
  background: radial-gradient(circle, rgba(99,102,241,0.12), transparent 70%);
}
.vacuum-particle {
  position: absolute;
  width: 4px;
  height: 4px;
  background: #cbd5e1;
  border-radius: 50%;
  box-shadow: 0 0 10px rgba(203, 213, 225, 0.5);
  animation: twinkle 4s ease-in-out infinite;
}
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
.answer-portal {
  perspective: 1200px;
}
.portal-glass {
  background: rgba(255, 255, 255, 0.7);
  backdrop-filter: blur(32px);
  border: 1px solid rgba(255, 255, 255, 0.9);
  box-shadow: 0 40px 100px -20px rgba(0,0,0,0.05), inset 0 1px 0 white;
}
.orbit {
  position: absolute;
  top: 50%;
  left: 50%;
  border-radius: 50%;
  border: 1.5px solid rgba(56, 189, 248, 0.1);
  transform: translate(-50%, -50%);
}
.orbit-1 {
  width: 110%;
  height: 110%;
  animation: rotate 30s linear infinite;
}
.orbit-2 {
  width: 130%;
  height: 130%;
  animation: rotate 45s linear infinite reverse;
}
.satellite {
  position: absolute;
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: white;
  box-shadow: 0 0 15px rgba(56, 189, 248, 0.5);
  border: 2px solid #0ea5e9;
}
.sat-1 {
  top: -6px;
  left: 50%;
}
.sat-2 {
  bottom: 50%;
  right: -6px;
}
.scene-frame {
  width: 100%;
  height: 100%;
}
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

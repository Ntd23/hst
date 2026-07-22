<template>
  <section
    class="technology-hero"
    :style="heroStyle"
    :aria-labelledby="titleId"
  >
    <div class="technology-hero__banner-effects" aria-hidden="true">
      <span class="technology-hero__light-sweep technology-hero__light-sweep--one" />
      <span class="technology-hero__light-sweep technology-hero__light-sweep--two" />
      <span class="technology-hero__light-sweep technology-hero__light-sweep--three" />

      <span class="technology-hero__flow-line technology-hero__flow-line--one"><i /></span>
      <span class="technology-hero__flow-line technology-hero__flow-line--two"><i /></span>
      <span class="technology-hero__flow-line technology-hero__flow-line--three"><i /></span>

      <span class="technology-hero__top-rail">
        <i v-for="node in 7" :key="`top-rail-${node}`" />
        <b />
      </span>

      <span class="technology-hero__data-bridge">
        <i class="technology-hero__data-bridge-line technology-hero__data-bridge-line--one"><b /></i>
        <i class="technology-hero__data-bridge-line technology-hero__data-bridge-line--two"><b /></i>
        <i class="technology-hero__data-bridge-line technology-hero__data-bridge-line--three"><b /></i>
        <em v-for="node in 5" :key="`bridge-node-${node}`" />
      </span>

      <div class="technology-hero__left-network">
        <span class="technology-hero__left-orbit technology-hero__left-orbit--one">
          <i />
          <i />
        </span>
        <span class="technology-hero__left-orbit technology-hero__left-orbit--two">
          <i />
        </span>

        <span class="technology-hero__left-pulse technology-hero__left-pulse--one" />
        <span class="technology-hero__left-pulse technology-hero__left-pulse--two" />

        <span class="technology-hero__left-stream technology-hero__left-stream--one"><i /></span>
        <span class="technology-hero__left-stream technology-hero__left-stream--two"><i /></span>
        <span class="technology-hero__left-stream technology-hero__left-stream--three"><i /></span>

        <span class="technology-hero__left-nodes">
          <i v-for="node in 8" :key="node" />
        </span>

        <span class="technology-hero__left-readout">
          <small>DATA FLOW / HST-01</small>
          <strong>SYNC ACTIVE</strong>
        </span>
      </div>

      <span class="technology-hero__corner-mark technology-hero__corner-mark--left" />
      <span class="technology-hero__corner-mark technology-hero__corner-mark--right" />
    </div>

    <ClientOnly>
      <BannerEnergyField
        :key="`banner-field-${sectionData.primary_color}-${sectionData.glow_color}`"
        :primary-color="sectionData.primary_color"
        :glow-color="sectionData.glow_color"
        :activation-signal="bannerPulseSignal"
      />
    </ClientOnly>

    <div class="technology-hero__ambient technology-hero__ambient--cyan" />
    <div class="technology-hero__ambient technology-hero__ambient--violet" />

    <UContainer class="technology-hero__container">
      <div class="technology-hero__layout">
        <div class="technology-hero__content">
          <div class="technology-hero__badge technology-hero__reveal technology-hero__reveal--badge">
            <span class="technology-hero__badge-dot" />
            <span>{{ sectionData.badge }}</span>
          </div>

          <h1
            :id="titleId"
            class="technology-hero__title technology-hero__reveal technology-hero__reveal--title"
          >
            <span class="technology-hero__title-base">{{ sectionData.title }}</span>
            <span class="technology-hero__title-highlight">{{ sectionData.highlight_text }}</span>
          </h1>

          <p class="technology-hero__description technology-hero__reveal technology-hero__reveal--description">
            {{ sectionData.description }}
          </p>

          <div class="technology-hero__actions technology-hero__reveal technology-hero__reveal--actions">
            <NuxtLink
              v-if="sectionData.primary_button"
              :to="sectionData.primary_url || '#'"
              class="technology-hero__button technology-hero__button--primary"
            >
              <span>{{ sectionData.primary_button }}</span>
              <span class="technology-hero__button-arrow" aria-hidden="true">→</span>
            </NuxtLink>

            <NuxtLink
              v-if="sectionData.secondary_button"
              :to="sectionData.secondary_url || '#'"
              class="technology-hero__button technology-hero__button--secondary"
            >
              {{ sectionData.secondary_button }}
            </NuxtLink>
          </div>

          <ul
            v-if="capabilities.length"
            class="technology-hero__capabilities technology-hero__reveal technology-hero__reveal--capabilities"
          >
            <li
              v-for="capability in capabilities"
              :key="capability"
              class="technology-hero__capability"
            >
              <span class="technology-hero__capability-dot" aria-hidden="true" />
              <span>{{ capability }}</span>
            </li>
          </ul>
        </div>

        <div class="technology-hero__visual" aria-hidden="true">
          <div class="technology-hero__visual-glow" />

          <div class="technology-hero__telemetry technology-hero__telemetry--signal">
            <span>Signal</span>
            <i />
            <strong>Stable</strong>
          </div>

          <div class="technology-hero__coordinates">
            <span>SYS / HST-01</span>
            <strong>08°41' N&nbsp;&nbsp;106°37' E</strong>
          </div>

          <div
            class="technology-hero__poster"
            :class="{
              'technology-hero__poster--hidden': enable3d && !coreUnavailable,
            }"
          >
            <NuxtImg
              v-if="sectionData.poster"
              :src="sectionData.poster"
              alt=""
              class="technology-hero__poster-image"
              loading="eager"
              fetchpriority="high"
            />

            <div v-else class="technology-hero__fallback-core">
              <span class="technology-hero__fallback-ring technology-hero__fallback-ring--one" />
              <span class="technology-hero__fallback-ring technology-hero__fallback-ring--two" />
              <span class="technology-hero__fallback-ring technology-hero__fallback-ring--three" />
              <span class="technology-hero__fallback-shape" />

              <span
                v-for="particle in 12"
                :key="particle"
                :class="`technology-hero__fallback-particle technology-hero__fallback-particle--${particle}`"
              />
            </div>
          </div>

          <ClientOnly v-if="enable3d">
            <TechnologyCore
              :key="`${sectionData.primary_color}-${sectionData.glow_color}`"
              :primary-color="sectionData.primary_color"
              :glow-color="sectionData.glow_color"
              @ready="handleCoreReady"
              @unavailable="handleCoreUnavailable"
              @pulse="handleCorePulse"
            />
          </ClientOnly>
        </div>
      </div>
    </UContainer>
  </section>
</template>

<script setup lang="ts">
import { ref, toRef, useId, watch } from "vue";
import BannerEnergyField from "~/components/cinematic/BannerEnergyField.client.vue";
import TechnologyCore from "~/components/cinematic/TechnologyCore.client.vue";
import { useTechnologyHeroShortcode } from "~/composables/shortcodes/useTechnologyHeroShortcode";

const props = defineProps<{
  data?: any;
}>();

const titleId = `${useId()}-title`;
const coreUnavailable = ref(false);
const bannerPulseSignal = ref(0);
const { sectionData, capabilities, enable3d, heroStyle } =
  useTechnologyHeroShortcode(toRef(props, "data"));

watch(enable3d, () => {
  coreUnavailable.value = false;
});

const handleCoreReady = () => {
  coreUnavailable.value = false;
};

const handleCoreUnavailable = () => {
  coreUnavailable.value = true;
};

const handleCorePulse = () => {
  bannerPulseSignal.value += 1;
};
</script>

<style scoped>
.technology-hero {
  --tech-hero-bg: #f7fbff;
  --tech-hero-bg-secondary: #edf6ff;
  --tech-hero-primary: #0866ff;
  --tech-hero-secondary: #2d8cff;
  --tech-hero-cyan: #35d6ff;
  --tech-hero-violet: #7567ff;
  --tech-hero-glow: #35d6ff;
  --tech-hero-title: #071a33;
  --tech-hero-text: #52647a;
  --tech-hero-border: rgba(8, 102, 255, 0.14);
  position: relative;
  isolation: isolate;
  min-height: max(48rem, 100vh);
  min-height: max(48rem, 100svh);
  overflow: hidden;
  background: linear-gradient(135deg, #ffffff 0%, var(--tech-hero-bg) 55%, var(--tech-hero-bg-secondary) 100%);
  background:
    radial-gradient(circle at 78% 42%, color-mix(in srgb, var(--tech-hero-glow) 13%, transparent), transparent 27%),
    radial-gradient(circle at 88% 24%, rgba(117, 103, 255, 0.08), transparent 23%),
    linear-gradient(135deg, #ffffff 0%, var(--tech-hero-bg) 55%, var(--tech-hero-bg-secondary) 100%);
  color: var(--tech-hero-title);
}

.technology-hero::before {
  position: absolute;
  z-index: -2;
  inset: -2rem;
  background-image:
    linear-gradient(rgba(8, 102, 255, 0.045) 1px, transparent 1px),
    linear-gradient(90deg, rgba(8, 102, 255, 0.045) 1px, transparent 1px);
  background-size: 48px 48px;
  content: "";
  mask-image: linear-gradient(90deg, transparent 0%, #000 18%, #000 82%, transparent 100%);
  opacity: 0.68;
  pointer-events: none;
}

.technology-hero__banner-effects {
  position: absolute;
  z-index: 0;
  inset: 0;
  overflow: hidden;
  pointer-events: none;
}

.technology-hero__light-sweep {
  position: absolute;
  top: -28%;
  width: clamp(9rem, 16vw, 18rem);
  height: 158%;
  background: linear-gradient(90deg, transparent, color-mix(in srgb, var(--tech-hero-glow) 8%, transparent), transparent);
  filter: blur(8px);
  opacity: 0;
  transform: rotate(14deg);
  animation: technology-hero-light-sweep 14s ease-in-out infinite;
  will-change: transform, opacity;
}

.technology-hero__light-sweep--one {
  left: -24%;
}

.technology-hero__light-sweep--two {
  left: -36%;
  width: clamp(6rem, 10vw, 11rem);
  animation-delay: -5.4s;
  animation-duration: 18s;
}

.technology-hero__light-sweep--three {
  display: none;
  left: -18%;
  width: clamp(4rem, 7vw, 8rem);
  animation-delay: -10.2s;
  animation-duration: 21s;
}

.technology-hero__flow-line {
  position: absolute;
  height: 1px;
  overflow: visible;
  background: linear-gradient(90deg, transparent, color-mix(in srgb, var(--tech-hero-primary) 14%, transparent), transparent);
  contain: layout style;
  opacity: 0.72;
}

.technology-hero__flow-line::before,
.technology-hero__flow-line::after {
  position: absolute;
  top: -0.16rem;
  width: 0.32rem;
  height: 0.32rem;
  border: 1px solid color-mix(in srgb, var(--tech-hero-primary) 28%, transparent);
  background: rgba(255, 255, 255, 0.82);
  content: "";
  transform: rotate(45deg);
}

.technology-hero__flow-line::before { left: 12%; }
.technology-hero__flow-line::after { right: 8%; }

.technology-hero__flow-line i {
  position: absolute;
  top: -0.14rem;
  left: 0;
  width: 0.28rem;
  height: 0.28rem;
  border-radius: 50%;
  background: var(--tech-hero-glow);
  box-shadow: 0 0 0.8rem var(--tech-hero-glow), 0 0 0 0.18rem color-mix(in srgb, var(--tech-hero-glow) 10%, transparent);
  animation: technology-hero-flow 6.4s ease-in-out infinite;
  will-change: opacity;
}

.technology-hero__flow-line--one {
  top: 25%;
  left: -3%;
  width: 39%;
}

.technology-hero__flow-line--two {
  right: -4%;
  bottom: 17%;
  width: 42%;
  transform: rotate(-3deg);
}

.technology-hero__flow-line--two i {
  animation-delay: -2.8s;
  animation-duration: 7.8s;
}

.technology-hero__flow-line--three {
  right: 30%;
  bottom: 6%;
  width: 18%;
  opacity: 0.42;
  transform: rotate(90deg);
  transform-origin: right center;
}

.technology-hero__flow-line--three i {
  animation-delay: -4.1s;
  animation-duration: 9s;
}

.technology-hero__top-rail {
  position: absolute;
  top: 18.5%;
  left: 17%;
  width: 65%;
  height: 1px;
  background: linear-gradient(
    90deg,
    transparent,
    color-mix(in srgb, var(--tech-hero-primary) 18%, transparent) 12%,
    color-mix(in srgb, var(--tech-hero-glow) 24%, transparent) 54%,
    transparent
  );
  opacity: 0.72;
}

.technology-hero__top-rail::before,
.technology-hero__top-rail::after {
  position: absolute;
  top: -0.18rem;
  width: 0.36rem;
  height: 0.36rem;
  border: 1px solid color-mix(in srgb, var(--tech-hero-primary) 44%, transparent);
  background: rgba(255, 255, 255, 0.88);
  content: "";
  transform: rotate(45deg);
}

.technology-hero__top-rail::before { left: 16%; }
.technology-hero__top-rail::after { right: 12%; }

.technology-hero__top-rail i {
  position: absolute;
  top: -0.11rem;
  width: 0.22rem;
  height: 0.22rem;
  border-radius: 50%;
  background: color-mix(in srgb, var(--tech-hero-primary) 62%, #ffffff);
  box-shadow: 0 0 0.6rem color-mix(in srgb, var(--tech-hero-glow) 54%, transparent);
  animation: technology-hero-top-node 4.8s ease-in-out infinite;
}

.technology-hero__top-rail i:nth-child(1) { left: 5%; animation-delay: -0.4s; }
.technology-hero__top-rail i:nth-child(2) { left: 28%; animation-delay: -2.1s; }
.technology-hero__top-rail i:nth-child(3) { left: 42%; animation-delay: -3.5s; }
.technology-hero__top-rail i:nth-child(4) { left: 57%; animation-delay: -1.2s; }
.technology-hero__top-rail i:nth-child(5) { left: 69%; animation-delay: -4.1s; }
.technology-hero__top-rail i:nth-child(6) { left: 83%; animation-delay: -2.8s; }
.technology-hero__top-rail i:nth-child(7) { left: 94%; animation-delay: -0.9s; }

.technology-hero__top-rail b {
  position: absolute;
  top: -0.12rem;
  left: 0;
  width: 0.26rem;
  height: 0.26rem;
  border-radius: 50%;
  background: var(--tech-hero-glow);
  box-shadow: 0 0 0.85rem var(--tech-hero-glow);
  animation: technology-hero-flow 8.6s ease-in-out infinite;
}

.technology-hero__data-bridge {
  position: absolute;
  z-index: 0;
  top: 40%;
  left: 45%;
  width: 11%;
  height: 25%;
  opacity: 0.72;
}

.technology-hero__data-bridge-line {
  position: absolute;
  left: 0;
  width: 100%;
  height: 1px;
  overflow: hidden;
  background: linear-gradient(90deg, transparent, color-mix(in srgb, var(--tech-hero-primary) 28%, transparent), transparent);
  transform-origin: center;
}

.technology-hero__data-bridge-line--one { top: 18%; transform: rotate(-8deg); }
.technology-hero__data-bridge-line--two { top: 50%; width: 88%; transform: rotate(3deg); }
.technology-hero__data-bridge-line--three { top: 76%; left: 14%; width: 74%; transform: rotate(-4deg); }

.technology-hero__data-bridge-line b {
  position: absolute;
  inset: 0 auto 0 0;
  width: 3.5rem;
  background: linear-gradient(90deg, transparent, var(--tech-hero-glow), transparent);
  box-shadow: 0 0 0.65rem color-mix(in srgb, var(--tech-hero-glow) 52%, transparent);
  animation: technology-hero-left-stream 5.6s ease-in-out infinite;
}

.technology-hero__data-bridge-line--two b { animation-delay: -2.2s; animation-duration: 6.8s; }
.technology-hero__data-bridge-line--three b { animation-delay: -4.1s; animation-duration: 7.7s; }

.technology-hero__data-bridge em {
  position: absolute;
  width: 0.3rem;
  height: 0.3rem;
  border: 1px solid color-mix(in srgb, var(--tech-hero-primary) 58%, transparent);
  background: rgba(255, 255, 255, 0.9);
  box-shadow: 0 0 0.55rem color-mix(in srgb, var(--tech-hero-glow) 56%, transparent);
  transform: rotate(45deg);
  animation: technology-hero-left-node 5.4s ease-in-out infinite;
}

.technology-hero__data-bridge em:nth-of-type(1) { top: 10%; left: 7%; animation-delay: -0.7s; }
.technology-hero__data-bridge em:nth-of-type(2) { top: 30%; right: 4%; animation-delay: -2.8s; }
.technology-hero__data-bridge em:nth-of-type(3) { top: 51%; left: 38%; animation-delay: -4.2s; }
.technology-hero__data-bridge em:nth-of-type(4) { right: 18%; bottom: 8%; animation-delay: -1.6s; }
.technology-hero__data-bridge em:nth-of-type(5) { bottom: 20%; left: 8%; animation-delay: -3.5s; }

.technology-hero__left-network {
  position: absolute;
  top: 13%;
  bottom: 3%;
  left: -10%;
  width: 55%;
  contain: layout paint style;
  mask-image: linear-gradient(
    90deg,
    #000 0 28%,
    rgba(0, 0, 0, 0.88) 38%,
    rgba(0, 0, 0, 0.28) 58%,
    transparent 78%
  );
  opacity: 0.8;
  pointer-events: none;
}

.technology-hero__left-network::before {
  position: absolute;
  inset: 15% 26% 12% 2%;
  border-radius: 50%;
  background: radial-gradient(
    ellipse,
    color-mix(in srgb, var(--tech-hero-glow) 9%, transparent),
    color-mix(in srgb, var(--tech-hero-primary) 4%, transparent) 45%,
    transparent 72%
  );
  content: "";
  animation: technology-hero-left-aura 7.5s ease-in-out infinite;
  will-change: transform, opacity;
}

.technology-hero__left-orbit {
  position: absolute;
  border: 1.5px solid color-mix(in srgb, var(--tech-hero-primary) 24%, transparent);
  border-radius: 50%;
  box-shadow:
    0 0 1.4rem color-mix(in srgb, var(--tech-hero-glow) 7%, transparent),
    inset 0 0 2rem color-mix(in srgb, var(--tech-hero-glow) 6%, transparent);
  animation: technology-hero-left-orbit 28s linear infinite;
  will-change: transform;
}

.technology-hero__left-orbit::before,
.technology-hero__left-orbit::after {
  position: absolute;
  border-radius: 50%;
  content: "";
}

.technology-hero__left-orbit::before {
  inset: 10%;
  border: 1px dashed color-mix(in srgb, var(--tech-hero-glow) 22%, transparent);
}

.technology-hero__left-orbit::after {
  inset: 27%;
  border: 1px solid color-mix(in srgb, var(--tech-hero-violet) 17%, transparent);
}

.technology-hero__left-orbit i {
  position: absolute;
  width: 0.42rem;
  height: 0.42rem;
  border-radius: 50%;
  background: var(--tech-hero-glow);
  box-shadow:
    0 0 0 0.2rem color-mix(in srgb, var(--tech-hero-glow) 9%, transparent),
    0 0 0.8rem color-mix(in srgb, var(--tech-hero-glow) 72%, transparent);
}

.technology-hero__left-orbit i:first-child {
  top: 15%;
  right: 13%;
}

.technology-hero__left-orbit i:nth-child(2) {
  bottom: 12%;
  left: 18%;
  background: var(--tech-hero-primary);
}

.technology-hero__left-orbit--one {
  top: 19%;
  left: -8%;
  width: min(31rem, 41vw);
  height: min(16rem, 22vw);
  transform: rotate(-8deg);
}

.technology-hero__left-orbit--two {
  top: 58%;
  left: 8%;
  width: min(22rem, 29vw);
  height: min(11rem, 15vw);
  border-color: color-mix(in srgb, var(--tech-hero-violet) 26%, transparent);
  animation-direction: reverse;
  animation-duration: 36s;
}

.technology-hero__left-pulse {
  position: absolute;
  width: 8rem;
  aspect-ratio: 1;
  border: 1.5px solid color-mix(in srgb, var(--tech-hero-glow) 38%, transparent);
  border-radius: 50%;
  opacity: 0;
  animation: technology-hero-left-pulse 5.8s ease-out infinite;
  will-change: transform, opacity;
}

.technology-hero__left-pulse--one {
  top: 38%;
  left: 7%;
}

.technology-hero__left-pulse--two {
  right: 9%;
  bottom: 14%;
  width: 5.5rem;
  border-color: color-mix(in srgb, var(--tech-hero-violet) 36%, transparent);
  animation-delay: -2.9s;
}

.technology-hero__left-stream {
  position: absolute;
  left: 4%;
  width: 88%;
  height: 1px;
  overflow: hidden;
  background: linear-gradient(90deg, transparent, color-mix(in srgb, var(--tech-hero-primary) 22%, transparent), transparent);
}

.technology-hero__left-stream i {
  position: absolute;
  inset: 0 auto 0 0;
  width: 9rem;
  background: linear-gradient(90deg, transparent, color-mix(in srgb, var(--tech-hero-glow) 88%, transparent), transparent);
  box-shadow: 0 0 0.8rem color-mix(in srgb, var(--tech-hero-glow) 52%, transparent);
  animation: technology-hero-left-stream 7.4s ease-in-out infinite;
  will-change: transform, opacity;
}

.technology-hero__left-stream--one {
  top: 12%;
  transform: rotate(2deg);
}

.technology-hero__left-stream--two {
  top: 78%;
  left: -2%;
  transform: rotate(-3deg);
}

.technology-hero__left-stream--two i {
  animation-delay: -3.1s;
  animation-duration: 9s;
}

.technology-hero__left-stream--three {
  bottom: 12%;
  left: 16%;
  width: 70%;
  transform: rotate(1deg);
}

.technology-hero__left-stream--three i {
  animation-delay: -5.2s;
  animation-duration: 11s;
}

.technology-hero__left-nodes {
  position: absolute;
  inset: 8% 4% 7% 3%;
}

.technology-hero__left-nodes i {
  position: absolute;
  width: 0.34rem;
  height: 0.34rem;
  border: 1px solid color-mix(in srgb, var(--tech-hero-primary) 62%, transparent);
  background: rgba(255, 255, 255, 0.92);
  box-shadow: 0 0 0.75rem color-mix(in srgb, var(--tech-hero-glow) 68%, transparent);
  transform: rotate(45deg);
  animation: technology-hero-left-node 6s ease-in-out infinite;
  will-change: transform, opacity;
}

.technology-hero__left-nodes i:nth-child(1) { top: 8%; left: 16%; animation-delay: -0.8s; }
.technology-hero__left-nodes i:nth-child(2) { top: 23%; right: 12%; animation-delay: -3.2s; }
.technology-hero__left-nodes i:nth-child(3) { top: 42%; left: 7%; animation-delay: -1.9s; }
.technology-hero__left-nodes i:nth-child(4) { top: 54%; left: 48%; animation-delay: -4.4s; }
.technology-hero__left-nodes i:nth-child(5) { right: 25%; bottom: 8%; animation-delay: -2.6s; }
.technology-hero__left-nodes i:nth-child(6) { bottom: 24%; left: 21%; animation-delay: -5.1s; }
.technology-hero__left-nodes i:nth-child(7) { top: 13%; left: 55%; animation-delay: -3.8s; }
.technology-hero__left-nodes i:nth-child(8) { right: 4%; bottom: 39%; animation-delay: -1.2s; }

.technology-hero__left-readout {
  position: absolute;
  bottom: 8%;
  left: 17%;
  display: grid;
  gap: 0.28rem;
  color: #7890a7;
  font-family: var(--font-tech, sans-serif);
  font-size: 0.48rem;
  font-weight: 700;
  letter-spacing: 0.14em;
  line-height: 1;
  text-transform: uppercase;
  animation: technology-hero-left-readout 4.8s ease-in-out infinite;
}

.technology-hero__left-readout small {
  font-size: inherit;
}

.technology-hero__left-readout strong {
  color: var(--tech-hero-primary);
  font-size: 0.54rem;
}

.technology-hero__corner-mark {
  position: absolute;
  width: 4.2rem;
  height: 4.2rem;
  border-color: color-mix(in srgb, var(--tech-hero-primary) 10%, transparent);
  border-style: solid;
  animation: technology-hero-corner-drift 7s ease-in-out infinite;
  will-change: transform, opacity;
}

.technology-hero__corner-mark--left {
  bottom: 5%;
  left: 2%;
  border-width: 0 0 1px 1px;
}

.technology-hero__corner-mark--right {
  top: 13%;
  right: 2%;
  border-width: 1px 1px 0 0;
  animation-delay: -3.5s;
  animation-direction: reverse;
}

.technology-hero__ambient {
  position: absolute;
  z-index: -1;
  border-radius: 999px;
  filter: blur(28px);
  pointer-events: none;
}

.technology-hero__ambient--cyan {
  top: 18%;
  right: 7%;
  width: min(34rem, 44vw);
  aspect-ratio: 1;
  background: color-mix(in srgb, var(--tech-hero-glow) 13%, transparent);
}

.technology-hero__ambient--violet {
  right: 28%;
  bottom: 3%;
  width: min(20rem, 26vw);
  aspect-ratio: 1;
  background: rgba(117, 103, 255, 0.08);
}

.technology-hero__container {
  position: relative;
  z-index: 1;
  display: flex;
  min-height: inherit;
  width: 100%;
  align-items: stretch;
}

.technology-hero__layout {
  display: grid;
  width: 100%;
  grid-template-columns: minmax(0, 1.08fr) minmax(0, 0.92fr);
  align-items: center;
  gap: clamp(1.25rem, 2.2vw, 2.5rem);
  padding-top: clamp(5.5rem, 9vh, 6.75rem);
  padding-bottom: clamp(2.5rem, 4vh, 3.5rem);
}

.technology-hero__content {
  position: relative;
  z-index: 2;
  min-width: 0;
  max-width: 46rem;
}

.technology-hero__badge {
  display: inline-flex;
  max-width: 100%;
  align-items: center;
  gap: 0.65rem;
  border: 1px solid var(--tech-hero-border);
  border-radius: 999px;
  padding: 0.55rem 0.9rem;
  background: rgba(255, 255, 255, 0.72);
  box-shadow: 0 10px 30px rgba(8, 102, 255, 0.07);
  color: #24527f;
  font-family: var(--font-tech, sans-serif);
  font-size: 0.78rem;
  font-weight: 700;
  letter-spacing: 0.08em;
  line-height: 1.3;
  text-transform: uppercase;
  backdrop-filter: blur(12px);
}

.technology-hero__badge-dot {
  width: 0.5rem;
  height: 0.5rem;
  flex: 0 0 auto;
  border-radius: 999px;
  background: linear-gradient(135deg, var(--tech-hero-primary), var(--tech-hero-glow));
  box-shadow: 0 0 0 0.25rem color-mix(in srgb, var(--tech-hero-primary) 10%, transparent);
  animation: technology-hero-badge-pulse 2.8s ease-in-out infinite;
}

.technology-hero__title {
  max-width: 16.5ch;
  margin: clamp(1.35rem, 2.4vw, 2rem) 0 0;
  color: var(--tech-hero-title);
  font-family: var(--font-tech, sans-serif);
  font-size: clamp(3.15rem, 4.1vw, 4.45rem);
  font-weight: 700;
  letter-spacing: -0.052em;
  line-height: 1.01;
  text-wrap: balance;
}

.technology-hero__title-base,
.technology-hero__title-highlight {
  display: block;
  overflow-wrap: anywhere;
}

.technology-hero__title-highlight {
  padding-bottom: 0.08em;
  background: linear-gradient(100deg, #075eff 2%, var(--tech-hero-secondary) 48%, var(--tech-hero-glow) 100%);
  background-clip: text;
  color: transparent;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.technology-hero__description {
  max-width: 37rem;
  margin: clamp(1.4rem, 2.4vw, 2rem) 0 0;
  color: var(--tech-hero-text);
  font-size: clamp(1rem, 1.25vw, 1.15rem);
  line-height: 1.78;
  text-wrap: pretty;
}

.technology-hero__actions {
  display: flex;
  flex-wrap: wrap;
  gap: 0.85rem;
  margin-top: clamp(1.65rem, 3vw, 2.35rem);
}

.technology-hero__button {
  position: relative;
  display: inline-flex;
  min-height: 3.4rem;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
  border-radius: 0.95rem;
  padding: 0.85rem 1.3rem;
  font-family: var(--font-tech, sans-serif);
  font-size: 0.93rem;
  font-weight: 700;
  line-height: 1.2;
  text-align: center;
  text-decoration: none;
  overflow: hidden;
  transition: transform 180ms ease, box-shadow 180ms ease, border-color 180ms ease, background-color 180ms ease;
}

.technology-hero__button > * {
  position: relative;
  z-index: 1;
}

.technology-hero__button--primary {
  border: 1px solid transparent;
  background: linear-gradient(135deg, var(--tech-hero-primary), var(--tech-hero-secondary));
  box-shadow: 0 14px 30px color-mix(in srgb, var(--tech-hero-primary) 25%, transparent);
  color: #ffffff;
}

.technology-hero__button--primary::before {
  position: absolute;
  top: -80%;
  bottom: -80%;
  left: -45%;
  width: 28%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
  content: "";
  pointer-events: none;
  transform: rotate(16deg);
  animation: technology-hero-button-sheen 5.2s ease-in-out infinite;
}

.technology-hero__button--secondary {
  border: 1px solid var(--tech-hero-border);
  background: rgba(255, 255, 255, 0.68);
  box-shadow: 0 10px 26px rgba(7, 26, 51, 0.06);
  color: #173653;
  backdrop-filter: blur(12px);
}

.technology-hero__button:hover {
  transform: translateY(-3px);
}

.technology-hero__button--primary:hover {
  box-shadow: 0 18px 36px color-mix(in srgb, var(--tech-hero-primary) 34%, transparent);
}

.technology-hero__button--secondary:hover {
  border-color: color-mix(in srgb, var(--tech-hero-primary) 34%, transparent);
  background: rgba(255, 255, 255, 0.88);
}

.technology-hero__button:active {
  transform: translateY(0);
}

.technology-hero__button:focus-visible {
  outline: 3px solid color-mix(in srgb, var(--tech-hero-glow) 48%, transparent);
  outline-offset: 4px;
}

.technology-hero__button-arrow {
  font-size: 1.15rem;
  line-height: 1;
  transition: transform 180ms ease;
}

.technology-hero__button--primary:hover .technology-hero__button-arrow {
  transform: translateX(0.2rem);
}

.technology-hero__capabilities {
  display: flex;
  flex-wrap: wrap;
  gap: 0.65rem 1rem;
  margin: clamp(1.5rem, 3vw, 2.25rem) 0 0;
  padding: 0;
  color: #607389;
  font-size: 0.78rem;
  font-weight: 600;
  list-style: none;
}

.technology-hero__capability {
  display: inline-flex;
  align-items: center;
  gap: 0.48rem;
}

.technology-hero__capability-dot {
  width: 0.35rem;
  height: 0.35rem;
  flex: 0 0 auto;
  border-radius: 999px;
  background: var(--tech-hero-primary);
  box-shadow: 0 0 0 0.2rem color-mix(in srgb, var(--tech-hero-primary) 8%, transparent);
  animation: technology-hero-capability-pulse 3s ease-in-out infinite;
}

.technology-hero__capability:nth-child(2) .technology-hero__capability-dot { animation-delay: -1s; }
.technology-hero__capability:nth-child(3) .technology-hero__capability-dot { animation-delay: -2s; }

.technology-hero__visual {
  position: relative;
  min-width: 0;
  height: clamp(31rem, 46vw, 42rem);
  max-height: 74svh;
}

.technology-hero__visual::after {
  position: absolute;
  z-index: 0;
  right: 7%;
  bottom: 7%;
  left: 7%;
  height: 13%;
  border-radius: 50%;
  background: radial-gradient(ellipse, rgba(8, 102, 255, 0.14), transparent 68%);
  filter: blur(18px);
  content: "";
  transform: perspective(22rem) rotateX(70deg);
}

.technology-hero__visual-glow {
  position: absolute;
  z-index: 0;
  top: 50%;
  left: 50%;
  width: min(31rem, 78%);
  aspect-ratio: 1;
  border-radius: 999px;
  background:
    radial-gradient(circle, rgba(255, 255, 255, 0.94) 0 9%, color-mix(in srgb, var(--tech-hero-glow) 16%, transparent) 30%, color-mix(in srgb, var(--tech-hero-primary) 7%, transparent) 52%, transparent 70%);
  filter: blur(10px);
  transform: translate(-50%, -50%);
  animation: technology-hero-glow-breathe 6.8s ease-in-out infinite;
  will-change: transform, opacity;
}

.technology-hero__telemetry,
.technology-hero__coordinates {
  position: absolute;
  z-index: 5;
  font-family: var(--font-tech, sans-serif);
  pointer-events: none;
}

.technology-hero__telemetry {
  display: grid;
  width: 8.4rem;
  grid-template-columns: 1fr auto;
  align-items: center;
  gap: 0.28rem 0.5rem;
  color: #426987;
  font-size: 0.54rem;
  font-weight: 800;
  letter-spacing: 0.12em;
  line-height: 1;
  text-transform: uppercase;
  animation: technology-hero-telemetry-float 5.8s ease-in-out infinite;
  will-change: transform;
}

.technology-hero__telemetry i {
  position: relative;
  grid-column: 1 / -1;
  height: 1px;
  overflow: hidden;
  background: linear-gradient(90deg, color-mix(in srgb, var(--tech-hero-primary) 58%, transparent), transparent);
}

.technology-hero__telemetry i::after {
  position: absolute;
  top: 0;
  left: 0;
  width: 32%;
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--tech-hero-glow), transparent);
  content: "";
  animation: technology-hero-data-line 3s ease-in-out infinite;
}

.technology-hero__telemetry strong {
  color: var(--tech-hero-primary);
  font-size: 0.54rem;
  font-weight: 800;
}

.technology-hero__telemetry--signal {
  right: 3%;
  bottom: 20%;
  animation-delay: -2.9s;
  animation-direction: reverse;
}

.technology-hero__telemetry--signal strong {
  color: #16aa82;
}

.technology-hero__coordinates {
  bottom: 17%;
  left: 4%;
  display: grid;
  gap: 0.24rem;
  color: #526f8a;
  font-size: 0.5rem;
  font-weight: 700;
  letter-spacing: 0.13em;
  line-height: 1.2;
  animation: technology-hero-data-drift 7.2s ease-in-out infinite;
  will-change: transform, opacity;
}

.technology-hero__coordinates strong {
  color: #1f5689;
  font-size: 0.54rem;
  font-weight: 700;
}

.technology-hero__poster {
  position: absolute;
  z-index: 3;
  inset: 0;
  display: grid;
  place-items: center;
  opacity: 1;
  pointer-events: none;
  transition: opacity 500ms ease;
}

.technology-hero__poster--hidden {
  opacity: 0;
  visibility: hidden;
}

.technology-hero__poster--hidden .technology-hero__fallback-shape,
.technology-hero__poster--hidden .technology-hero__fallback-ring,
.technology-hero__poster--hidden .technology-hero__fallback-particle {
  animation-play-state: paused;
}

.technology-hero__poster-image {
  width: 100%;
  height: 100%;
  object-fit: contain;
}

.technology-hero__fallback-core {
  position: relative;
  width: min(34rem, 86%);
  aspect-ratio: 1;
  transform-style: preserve-3d;
}

.technology-hero__fallback-shape {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 35%;
  aspect-ratio: 1;
  border: 1px solid rgba(255, 255, 255, 0.95);
  border-radius: 35% 48% 42% 50%;
  background:
    linear-gradient(145deg, rgba(255, 255, 255, 0.96), rgba(194, 230, 255, 0.78) 48%, color-mix(in srgb, var(--tech-hero-primary) 30%, white));
  box-shadow:
    0 0 3rem color-mix(in srgb, var(--tech-hero-glow) 38%, transparent),
    0 1.5rem 4rem rgba(8, 102, 255, 0.18),
    inset 0.8rem 0.8rem 1.6rem rgba(255, 255, 255, 0.9),
    inset -0.8rem -0.8rem 1.8rem rgba(8, 102, 255, 0.16);
  transform: translate(-50%, -50%) rotate(35deg);
  animation: technology-hero-core-float 6s ease-in-out infinite;
}

.technology-hero__fallback-shape::before,
.technology-hero__fallback-shape::after {
  position: absolute;
  border-radius: inherit;
  content: "";
}

.technology-hero__fallback-shape::before {
  inset: 12%;
  border: 1px solid rgba(8, 102, 255, 0.16);
}

.technology-hero__fallback-shape::after {
  inset: 28%;
  background: radial-gradient(circle at 35% 30%, #ffffff, var(--tech-hero-glow) 38%, var(--tech-hero-primary) 100%);
  box-shadow: 0 0 1.75rem color-mix(in srgb, var(--tech-hero-glow) 60%, transparent);
}

.technology-hero__fallback-ring {
  position: absolute;
  top: 50%;
  left: 50%;
  border: 1px solid color-mix(in srgb, var(--tech-hero-primary) 28%, transparent);
  border-radius: 50%;
  box-shadow: 0 0 1.5rem color-mix(in srgb, var(--tech-hero-glow) 12%, transparent);
}

.technology-hero__fallback-ring--one {
  width: 58%;
  height: 58%;
  transform: translate(-50%, -50%) rotateX(64deg) rotateZ(12deg);
  animation: technology-hero-ring-one 15s linear infinite;
}

.technology-hero__fallback-ring--two {
  width: 75%;
  height: 42%;
  border-color: color-mix(in srgb, var(--tech-hero-glow) 42%, transparent);
  transform: translate(-50%, -50%) rotate(55deg);
  animation: technology-hero-ring-two 19s linear infinite reverse;
}

.technology-hero__fallback-ring--three {
  width: 88%;
  height: 62%;
  border-color: rgba(117, 103, 255, 0.22);
  transform: translate(-50%, -50%) rotate(-28deg);
  animation: technology-hero-ring-three 24s linear infinite;
}

.technology-hero__fallback-particle {
  position: absolute;
  width: 0.4rem;
  height: 0.4rem;
  border-radius: 999px;
  background: #ffffff;
  box-shadow: 0 0 0.8rem var(--tech-hero-glow);
  animation: technology-hero-particle 4.8s ease-in-out infinite;
}

.technology-hero__fallback-particle--1 { top: 14%; left: 34%; animation-delay: -0.4s; }
.technology-hero__fallback-particle--2 { top: 27%; right: 11%; animation-delay: -1.8s; }
.technology-hero__fallback-particle--3 { right: 21%; bottom: 18%; animation-delay: -3.2s; }
.technology-hero__fallback-particle--4 { bottom: 11%; left: 36%; animation-delay: -0.9s; }
.technology-hero__fallback-particle--5 { top: 43%; left: 6%; animation-delay: -2.4s; }
.technology-hero__fallback-particle--6 { top: 9%; right: 29%; animation-delay: -1.2s; }
.technology-hero__fallback-particle--7 { right: 4%; bottom: 43%; animation-delay: -3.8s; }
.technology-hero__fallback-particle--8 { bottom: 24%; left: 13%; animation-delay: -2.8s; }
.technology-hero__fallback-particle--9 { top: 29%; left: 22%; animation-delay: -4.1s; }
.technology-hero__fallback-particle--10 { top: 47%; right: 27%; animation-delay: -1.5s; }
.technology-hero__fallback-particle--11 { right: 38%; bottom: 7%; animation-delay: -3.5s; }
.technology-hero__fallback-particle--12 { top: 6%; left: 49%; animation-delay: -2.1s; }

.technology-hero__reveal {
  animation: technology-hero-reveal 460ms both cubic-bezier(0.22, 1, 0.36, 1);
}

.technology-hero__reveal--badge { animation-delay: 20ms; }
.technology-hero__reveal--title { animation-delay: 60ms; }
.technology-hero__reveal--description { animation-delay: 100ms; }
.technology-hero__reveal--actions { animation-delay: 140ms; }
.technology-hero__reveal--capabilities { animation-delay: 180ms; }

@keyframes technology-hero-reveal {
  from { opacity: 0; transform: translateY(1rem); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes technology-hero-core-float {
  0%, 100% { transform: translate(-50%, -50%) rotate(35deg) translateY(0); }
  50% { transform: translate(-50%, -50%) rotate(39deg) translateY(-0.75rem); }
}

@keyframes technology-hero-ring-one {
  to { transform: translate(-50%, -50%) rotateX(64deg) rotateZ(372deg); }
}

@keyframes technology-hero-ring-two {
  to { transform: translate(-50%, -50%) rotate(415deg); }
}

@keyframes technology-hero-ring-three {
  to { transform: translate(-50%, -50%) rotate(332deg); }
}

@keyframes technology-hero-particle {
  0%, 100% { opacity: 0.4; transform: translateY(0) scale(0.75); }
  50% { opacity: 1; transform: translateY(-0.65rem) scale(1); }
}

@keyframes technology-hero-data-line {
  0% { opacity: 0; transform: translateX(-120%); }
  35%, 70% { opacity: 1; }
  100% { opacity: 0; transform: translateX(330%); }
}

@keyframes technology-hero-light-sweep {
  0%, 6% { opacity: 0; transform: translateX(0) rotate(14deg); }
  20% { opacity: 0.72; }
  78% { opacity: 0.34; }
  94%, 100% { opacity: 0; transform: translateX(145vw) rotate(14deg); }
}

@keyframes technology-hero-flow {
  0%, 8% { left: 0; opacity: 0; }
  22% { opacity: 1; }
  82% { opacity: 0.85; }
  94%, 100% { left: calc(100% - 0.28rem); opacity: 0; }
}

@keyframes technology-hero-top-node {
  0%, 100% { opacity: 0.28; transform: scale(0.72); }
  50% { opacity: 0.92; transform: scale(1.12); }
}

@keyframes technology-hero-glow-breathe {
  0%, 100% {
    opacity: 0.78;
    transform: translate(-50%, -50%) scale(0.97);
  }
  50% {
    opacity: 1;
    transform: translate(-50%, -50%) scale(1.04);
  }
}

@keyframes technology-hero-telemetry-float {
  0%, 100% { transform: translate3d(0, 0, 0); }
  50% { transform: translate3d(0.35rem, -0.55rem, 0); }
}

@keyframes technology-hero-data-drift {
  0%, 100% { opacity: 0.64; transform: translate3d(0, 0, 0); }
  50% { opacity: 1; transform: translate3d(-0.25rem, -0.38rem, 0); }
}

@keyframes technology-hero-corner-drift {
  0%, 100% { opacity: 0.48; transform: translate3d(0, 0, 0); }
  50% { opacity: 0.9; transform: translate3d(0.3rem, -0.3rem, 0); }
}

@keyframes technology-hero-left-orbit {
  from { transform: rotate(-8deg); }
  to { transform: rotate(352deg); }
}

@keyframes technology-hero-left-aura {
  0%, 100% { opacity: 0.55; transform: scale(0.96); }
  50% { opacity: 1; transform: scale(1.05); }
}

@keyframes technology-hero-left-pulse {
  0% { opacity: 0; transform: scale(0.55); }
  24% { opacity: 0.82; }
  72% { opacity: 0.36; }
  100% { opacity: 0; transform: scale(1.75); }
}

@keyframes technology-hero-left-stream {
  0%, 8% { opacity: 0; transform: translate3d(-8rem, 0, 0); }
  24% { opacity: 0.9; }
  78% { opacity: 0.65; }
  94%, 100% { opacity: 0; transform: translate3d(52vw, 0, 0); }
}

@keyframes technology-hero-left-node {
  0%, 100% { opacity: 0.35; transform: translate3d(0, 0, 0) rotate(45deg) scale(0.75); }
  50% { opacity: 1; transform: translate3d(0.45rem, -0.7rem, 0) rotate(135deg) scale(1.05); }
}

@keyframes technology-hero-left-readout {
  0%, 100% { opacity: 0.52; transform: translate3d(0, 0.15rem, 0); }
  50% { opacity: 1; transform: translate3d(0, -0.15rem, 0); }
}

@keyframes technology-hero-badge-pulse {
  0%, 100% { box-shadow: 0 0 0 0.22rem color-mix(in srgb, var(--tech-hero-primary) 8%, transparent), 0 0 0 transparent; }
  50% { box-shadow: 0 0 0 0.34rem color-mix(in srgb, var(--tech-hero-primary) 12%, transparent), 0 0 1rem color-mix(in srgb, var(--tech-hero-glow) 46%, transparent); }
}

@keyframes technology-hero-title-shimmer {
  0%, 15%, 100% { background-position: 0% center; }
  55%, 75% { background-position: 100% center; }
}

@keyframes technology-hero-button-sheen {
  0%, 58% { opacity: 0; transform: translateX(0) rotate(16deg); }
  66% { opacity: 1; }
  82% { opacity: 0.75; }
  94%, 100% { opacity: 0; transform: translateX(560%) rotate(16deg); }
}

@keyframes technology-hero-capability-pulse {
  0%, 100% { opacity: 0.55; transform: scale(0.82); }
  50% { opacity: 1; transform: scale(1.1); }
}

@media (max-width: 1180px) {
  .technology-hero__data-bridge {
    display: none;
  }

  .technology-hero__layout {
    grid-template-columns: minmax(0, 1.02fr) minmax(0, 0.98fr);
    gap: 1.5rem;
  }

  .technology-hero__title {
    max-width: 15.5ch;
    font-size: clamp(3rem, 4.55vw, 4.15rem);
  }
}

@media (max-width: 1023px) {
  .technology-hero {
    min-height: auto;
  }

  .technology-hero__layout {
    grid-template-columns: minmax(0, 1fr);
    gap: 0.75rem;
    padding-top: 7.5rem;
    padding-bottom: 2.75rem;
  }

  .technology-hero__top-rail {
    display: none;
  }

  .technology-hero__content {
    max-width: 44rem;
  }

  .technology-hero__title {
    max-width: 13ch;
    font-size: clamp(3rem, 8vw, 4.65rem);
  }

  .technology-hero__visual {
    height: clamp(23rem, 58vw, 32rem);
    max-height: none;
  }

  .technology-hero__fallback-core {
    width: min(29rem, 72%);
  }

  .technology-hero__light-sweep--two {
    display: none;
  }
}

@media (max-width: 767px) {
  .technology-hero::before {
    background-size: 36px 36px;
    mask-image: linear-gradient(180deg, transparent 0%, #000 18%, #000 78%, transparent 100%);
    opacity: 0.5;
  }

  .technology-hero__light-sweep--two,
  .technology-hero__light-sweep--three,
  .technology-hero__flow-line--three {
    display: none;
  }

  .technology-hero__flow-line--one {
    top: 31%;
    width: 55%;
    opacity: 0.44;
  }

  .technology-hero__flow-line--two {
    bottom: 8%;
    width: 58%;
    opacity: 0.48;
  }

  .technology-hero__left-network {
    top: 14%;
    bottom: 43%;
    left: -34%;
    width: 128%;
    opacity: 0.46;
  }

  .technology-hero__left-orbit--one {
    top: 20%;
    left: 9%;
    width: 25rem;
    height: 13rem;
  }

  .technology-hero__left-orbit--two,
  .technology-hero__left-pulse--two,
  .technology-hero__left-stream--three,
  .technology-hero__left-readout,
  .technology-hero__left-nodes i:nth-child(n + 6) {
    display: none;
  }

  .technology-hero__corner-mark {
    width: 2.8rem;
    height: 2.8rem;
  }

  .technology-hero__layout {
    padding-top: 7rem;
    padding-bottom: 2.25rem;
  }

  .technology-hero__badge {
    padding: 0.5rem 0.75rem;
    font-size: 0.68rem;
  }

  .technology-hero__title {
    max-width: 14ch;
    margin-top: 1.15rem;
    font-size: clamp(2.55rem, 11.2vw, 4rem);
    letter-spacing: -0.05em;
    line-height: 1.02;
  }

  .technology-hero__description {
    margin-top: 1.15rem;
    font-size: 0.96rem;
    line-height: 1.68;
  }

  .technology-hero__actions {
    margin-top: 1.4rem;
  }

  .technology-hero__capabilities {
    margin-top: 1.4rem;
  }

  .technology-hero__visual {
    height: clamp(18.5rem, 78vw, 24rem);
  }

  .technology-hero__telemetry {
    width: 7.25rem;
    font-size: 0.46rem;
  }

  .technology-hero__telemetry strong {
    font-size: 0.46rem;
  }

  .technology-hero__telemetry--signal {
    right: 0;
    bottom: 15%;
  }

  .technology-hero__coordinates {
    display: none;
  }

  .technology-hero__fallback-core {
    width: min(24rem, 90%);
  }

  .technology-hero__fallback-particle--7,
  .technology-hero__fallback-particle--8,
  .technology-hero__fallback-particle--9,
  .technology-hero__fallback-particle--10,
  .technology-hero__fallback-particle--11,
  .technology-hero__fallback-particle--12 {
    display: none;
  }
}

@media (max-width: 479px) {
  .technology-hero__layout {
    gap: 0.4rem;
    padding-top: 6.75rem;
    padding-bottom: 1.75rem;
  }

  .technology-hero__title {
    font-size: clamp(2.35rem, 11.8vw, 3.2rem);
  }

  .technology-hero__actions {
    display: grid;
    grid-template-columns: minmax(0, 1fr);
  }

  .technology-hero__button {
    width: 100%;
    min-height: 3.25rem;
  }

  .technology-hero__capabilities {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
    gap: 0.55rem;
  }

  .technology-hero__visual {
    height: clamp(17.5rem, 80vw, 21rem);
  }
}

@media (max-width: 389px) {
  .technology-hero__title {
    font-size: 2.35rem;
  }

  .technology-hero__description {
    font-size: 0.91rem;
  }

  .technology-hero__capabilities {
    grid-template-columns: minmax(0, 1fr);
  }

  .technology-hero__visual {
    height: 17.5rem;
  }
}

@media (prefers-reduced-motion: reduce) {
  .technology-hero__reveal,
  .technology-hero__light-sweep,
  .technology-hero__flow-line i,
  .technology-hero__top-rail i,
  .technology-hero__top-rail b,
  .technology-hero__data-bridge-line b,
  .technology-hero__data-bridge em,
  .technology-hero__left-network::before,
  .technology-hero__left-orbit,
  .technology-hero__left-pulse,
  .technology-hero__left-stream i,
  .technology-hero__left-nodes i,
  .technology-hero__left-readout,
  .technology-hero__corner-mark,
  .technology-hero__badge-dot,
  .technology-hero__title-highlight,
  .technology-hero__button--primary::before,
  .technology-hero__capability-dot,
  .technology-hero__visual-glow,
  .technology-hero__telemetry,
  .technology-hero__telemetry i::after,
  .technology-hero__coordinates,
  .technology-hero__fallback-shape,
  .technology-hero__fallback-ring,
  .technology-hero__fallback-particle {
    animation: none;
  }

  .technology-hero__button,
  .technology-hero__button-arrow,
  .technology-hero__poster {
    transition-duration: 0.01ms;
  }
}
</style>

<template>
  <section class="team-section relative overflow-hidden py-14 sm:py-18">
    <UContainer class="relative z-10">
      <div
        v-if="sectionData.title || sectionData.subtitle"
        v-motion
        :initial="{ opacity: 0, y: 22 }"
        :visible-once="{ opacity: 1, y: 0, transition: { duration: 600 } }"
        class="mx-auto mb-10 max-w-3xl text-center"
      >
        <div v-if="sectionData.subtitle" class="team-kicker">
          <span class="team-kicker__dot" />
          <span v-html="sectionData.subtitle" />
        </div>
        <h2
          v-if="sectionData.title"
          class="mt-4 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl lg:text-5xl"
          v-html="sectionData.title"
        />
      </div>

      <div class="team-grid">
        <article
          v-for="(member, i) in team"
          :key="member.id || member.name || i"
          v-motion
          :initial="{ opacity: 0, y: 20 }"
          :visible-once="{ opacity: 1, y: 0, transition: { duration: 560, delay: Number(i) * 60 } }"
          class="member-bento group"
        >
          <div class="bento-main__media">
            <NuxtImg
              v-if="member.photo"
              :src="member.photo"
              :alt="member.name || 'Team member'"
              class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-[1.05]"
              loading="lazy"
            />
            <div v-else class="bento-main__fallback">
              <UIcon name="i-lucide-user-round" class="size-8 text-white/90" />
            </div>

            <div class="bento-main__overlay opacity-60 group-hover:opacity-80 transition-opacity duration-500" />

            <div v-if="socialEntries(member.socials).length" class="bento-main__socials translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
              <a
                v-for="social in socialEntries(member.socials)"
                :key="social.name"
                :href="social.url"
                target="_blank"
                rel="noreferrer"
                class="bento-social"
                :aria-label="social.name"
              >
                <UIcon :name="socialIcon(social.name)" class="size-3.5" />
              </a>
            </div>

            <div class="bento-main__content p-3 sm:p-4">
              <div v-if="member.location" class="bento-location mb-1">
                <UIcon name="i-lucide-map-pinned" class="size-3" />
                <span class="text-[9px]">{{ member.location }}</span>
              </div>

              <h3 class="text-base sm:text-lg font-black leading-tight text-white line-clamp-1">
                {{ member.name }}
              </h3>

              <p v-if="member.title" class="text-[10px] sm:text-xs font-bold text-sky-200 uppercase tracking-wider mt-0.5 opacity-80">
                {{ member.title }}
              </p>

              <div
                v-if="member.content"
                class="bento-main__summary line-clamp-2 mt-2"
                v-html="member.content"
              />
            </div>
          </div>
        </article>
      </div>
    </UContainer>
  </section>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
  data?: any
}>()

const rootData = computed(() => props.data?.content || props.data || {})
const sectionData = computed(() => rootData.value?.data || rootData.value || {})
const team = computed(() => rootData.value?.items || [])

const socialEntries = (socials?: Record<string, string>) =>
  Object.entries(socials || {})
    .filter(([, value]) => Boolean(value))
    .map(([name, url]) => ({ name, url }))

const socialIcon = (name: string) => {
  const normalized = String(name).toLowerCase()

  if (normalized === 'facebook') return 'i-simple-icons-facebook'
  if (normalized === 'instagram') return 'i-simple-icons-instagram'
  if (normalized === 'twitter') return 'i-simple-icons-x'
  if (normalized === 'linkedin') return 'i-simple-icons-linkedin'
  if (normalized === 'youtube') return 'i-simple-icons-youtube'

  return 'i-lucide-link'
}
</script>

<style scoped>
.team-section {
  background: transparent;
  font-family: var(--font-body, sans-serif);
}
.team-section h2,
.team-section h3,
.team-kicker {
  font-family: var(--font-tech, sans-serif);
}

.team-kicker {
  display: inline-flex;
  align-items: center;
  gap: 0.45rem;
  padding: 0.35rem 0.75rem;
  border-radius: 999px;
  border: 1px solid rgba(125, 211, 252, 0.4);
  color: #0369a1;
  font-size: 0.7rem;
  font-weight: 800;
  letter-spacing: 0.15em;
  text-transform: uppercase;
  background: white;
}

.team-kicker__dot {
  width: 0.35rem;
  height: 0.35rem;
  border-radius: 999px;
  background: #0ea5e9;
}

.team-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 0.75rem;
}

@media (min-width: 640px) {
  .team-grid {
    gap: 1.25rem;
  }
}

@media (min-width: 1024px) {
  .team-grid {
    grid-template-columns: repeat(4, 1fr);
  }
}

.member-bento {
  position: relative;
  width: 100%;
  border-radius: 1.25rem;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
  transition: transform 0.4s ease;
}

.member-bento:hover {
  transform: translateY(-5px);
}

.bento-main__media {
  position: relative;
  aspect-ratio: 1 / 1.15;
  background: #0f172a;
}

.bento-main__fallback {
  display: flex;
  height: 100%;
  align-items: center;
  justify-content: center;
}

.bento-main__overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(180deg, transparent 40%, rgba(15, 23, 42, 0.95) 100%);
  z-index: 1;
}

.bento-main__socials {
  position: absolute;
  top: 0.75rem;
  right: 0.75rem;
  display: flex;
  gap: 0.4rem;
  z-index: 5;
}

.bento-social {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 1.75rem;
  height: 1.75rem;
  border-radius: 50%;
  color: white;
  background: rgba(15, 23, 42, 0.5);
  backdrop-filter: blur(8px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  transition: all 0.2s ease;
}

.bento-social:hover {
  background: #0ea5e9;
  transform: scale(1.1);
}

.bento-main__content {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  z-index: 2;
  color: white;
}

.bento-location {
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  padding: 0.15rem 0.5rem;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 999px;
  backdrop-filter: blur(4px);
}

.bento-main__summary {
  font-size: 0.75rem;
  color: rgba(255, 255, 255, 0.7);
  line-height: 1.4;
}

.bento-main__summary :deep(div),
.bento-main__summary :deep(p) {
  display: inline;
}
</style>

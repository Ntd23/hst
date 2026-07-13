<template>
  <section class="services-tab-section relative overflow-hidden py-14 sm:py-20">
    <div v-if="sectionData.background_image" class="section-background">
      <NuxtImg
        :src="sectionData.background_image"
        alt=""
        class="h-full w-full object-cover"
        :loading="imageLoading"
      />
      <div class="section-background__overlay" />
    </div>

    <UContainer class="relative z-10">
      <div class="section-header">
        <CommonsSectionHeading
          :title="sectionData.title"
          :subtitle="sectionData.subtitle"
          align="left"
        />

        <NuxtLink
          v-if="sectionData.button?.label && sectionData.button?.url"
          :to="sectionData.button.url"
          class="btn-shared-cta section-cta"
        >
          <span>{{ sectionData.button.label }}</span>
          <span class="btn-shared-cta__icon">
            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M5 12h14" />
              <path d="m13 6 6 6-6 6" />
            </svg>
          </span>
        </NuxtLink>
      </div>

      <div v-if="tabs.length" class="tabs-layout">
        <div class="tab-list" role="tablist" aria-label="Services">
          <button
            v-for="(tab, index) in tabs"
            :key="tab.service_id || index"
            type="button"
            role="tab"
            :aria-selected="activeIndex === index"
            :tabindex="activeIndex === index ? 0 : -1"
            :class="['tab-button', { 'tab-button--active': activeIndex === index }]"
            @click="setActiveTab(index)"
          >
            <span class="tab-button__icon">
              <NuxtImg
                v-if="tab.service?.icon_image"
                :src="tab.service.icon_image"
                alt=""
                class="size-5 object-contain"
              />
              <svg v-else class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 12h.01" />
                <path d="M16 6V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2" />
                <path d="M22 13a18.15 18.15 0 0 1-20 0" />
                <rect width="20" height="14" x="2" y="6" rx="2" />
              </svg>
            </span>
            <span class="line-clamp-2">{{ tab.service?.name || tab.title }}</span>
            <svg class="tab-button__arrow size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="m9 18 6-6-6-6" />
            </svg>
          </button>
        </div>

        <Transition name="tab-fade" mode="out-in">
          <article
            v-if="activeTab"
            :key="activeTab.service_id"
            class="tab-panel"
            role="tabpanel"
          >
            <div class="tab-panel__media">
              <NuxtImg
                v-if="activeTab.image"
                :src="activeTab.image"
                :alt="activeTab.title || activeTab.service?.name || ''"
                class="h-full w-full object-cover"
                :loading="imageLoading"
              />
              <div v-else class="tab-panel__fallback">
                <svg class="size-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <rect width="18" height="18" x="3" y="3" rx="2" />
                  <path d="M3 9h18M9 21V9" />
                </svg>
              </div>
            </div>

            <div class="tab-panel__content">
              <p v-if="activeTab.service?.name" class="service-label">
                {{ activeTab.service.name }}
              </p>
              <h3 v-if="activeTab.title" v-html="activeTab.title" />
              <p
                v-if="activeTab.description"
                class="tab-description"
                v-html="activeTab.description"
              />

              <ul v-if="activeTab.featured_titles?.length" class="feature-list">
                <li v-for="feature in activeTab.featured_titles" :key="feature">
                  <span class="feature-check">
                    <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                      <path d="m5 12 4 4L19 6" />
                    </svg>
                  </span>
                  <span>{{ feature }}</span>
                </li>
              </ul>

              <NuxtLink :to="tabUrl(activeTab)" class="btn-shared-cta tab-panel__cta">
                <span>{{ activeTab.button_label || $t("services.explore") }}</span>
                <span class="btn-shared-cta__icon">
                  <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M5 12h14" />
                    <path d="m13 6 6 6-6 6" />
                  </svg>
                </span>
              </NuxtLink>
            </div>
          </article>
        </Transition>
      </div>
    </UContainer>
  </section>
</template>

<script setup lang="ts">
import CommonsSectionHeading from "~/components/commons/SectionHeading.vue";

const props = defineProps<{
  data?: any;
}>();

const {
  sectionData,
  tabs,
  activeIndex,
  activeTab,
  imageLoading,
  setActiveTab,
  tabUrl,
} = useServicesTabShortcode(toRef(props, "data"));
</script>

<style scoped>
.services-tab-section {
  background: #f8fafc;
  font-family: var(--font-body, sans-serif);
}

.section-background,
.section-background__overlay {
  position: absolute;
  inset: 0;
}

.section-background__overlay {
  background: linear-gradient(135deg, rgba(248, 250, 252, 0.97), rgba(240, 249, 255, 0.9));
}

.section-header {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  gap: 2rem;
}

.section-header :deep(.section-heading) {
  margin-bottom: 2rem;
}

.section-cta {
  flex-shrink: 0;
  margin-bottom: 2rem;
}

.tabs-layout {
  display: grid;
  grid-template-columns: minmax(230px, 0.78fr) minmax(0, 2fr);
  gap: 1.25rem;
}

.tab-list {
  display: flex;
  flex-direction: column;
  gap: 0.65rem;
}

.tab-button {
  display: grid;
  grid-template-columns: auto minmax(0, 1fr) auto;
  align-items: center;
  gap: 0.8rem;
  width: 100%;
  padding: 0.9rem 1rem;
  border: 1px solid rgba(148, 163, 184, 0.2);
  border-radius: 1rem;
  background: rgba(255, 255, 255, 0.8);
  color: #334155;
  font-size: 0.86rem;
  font-weight: 700;
  line-height: 1.35;
  text-align: left;
  box-shadow: 0 8px 24px rgba(15, 23, 42, 0.04);
  transition: 0.25s ease;
}

.tab-button:hover,
.tab-button--active {
  border-color: rgba(14, 165, 233, 0.35);
  background: #fff;
  color: #0369a1;
  transform: translateX(4px);
  box-shadow: 0 14px 35px rgba(14, 165, 233, 0.1);
}

.tab-button__icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 2.25rem;
  height: 2.25rem;
  border-radius: 0.75rem;
  color: #0284c7;
  background: #e0f2fe;
}

.tab-button__arrow {
  color: #94a3b8;
}

.tab-panel {
  display: grid;
  grid-template-columns: minmax(0, 1.05fr) minmax(0, 1fr);
  min-height: 430px;
  overflow: hidden;
  border: 1px solid rgba(148, 163, 184, 0.18);
  border-radius: 1.5rem;
  background: rgba(255, 255, 255, 0.94);
  box-shadow: 0 24px 60px rgba(15, 23, 42, 0.1);
}

.tab-panel__media {
  min-height: 320px;
  overflow: hidden;
  background: #e2e8f0;
}

.tab-panel__media img {
  transition: transform 0.7s ease;
}

.tab-panel:hover .tab-panel__media img {
  transform: scale(1.035);
}

.tab-panel__fallback {
  display: flex;
  height: 100%;
  align-items: center;
  justify-content: center;
  color: #38bdf8;
  background: linear-gradient(135deg, #e0f2fe, #d1fae5);
}

.tab-panel__content {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  justify-content: center;
  padding: clamp(1.5rem, 3vw, 2.75rem);
}

.service-label {
  margin-bottom: 0.65rem;
  color: #0284c7;
  font-family: var(--font-tech, sans-serif);
  font-size: 0.7rem;
  font-weight: 800;
  letter-spacing: 0.12em;
  text-transform: uppercase;
}

.tab-panel h3 {
  margin: 0;
  color: #0f172a;
  font-family: var(--font-tech, sans-serif);
  font-size: clamp(1.55rem, 2.5vw, 2.35rem);
  font-weight: 800;
  line-height: 1.15;
  letter-spacing: -0.035em;
}

.tab-description {
  margin: 1rem 0 0;
  color: #64748b;
  font-size: 0.93rem;
  line-height: 1.7;
}

.feature-list {
  display: grid;
  gap: 0.65rem;
  margin: 1.25rem 0 0;
  padding: 0;
  list-style: none;
}

.feature-list li {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  color: #334155;
  font-size: 0.86rem;
  font-weight: 700;
}

.feature-check {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 1.35rem;
  height: 1.35rem;
  flex-shrink: 0;
  border-radius: 999px;
  color: #047857;
  background: #d1fae5;
}

.tab-panel__cta {
  margin-top: 1.5rem;
}

.tab-fade-enter-active,
.tab-fade-leave-active {
  transition: opacity 0.18s ease, transform 0.18s ease;
}

.tab-fade-enter-from,
.tab-fade-leave-to {
  opacity: 0;
  transform: translateY(8px);
}

@media (max-width: 900px) {
  .section-header {
    display: block;
  }

  .section-cta {
    margin-top: -0.75rem;
  }

  .tabs-layout {
    grid-template-columns: 1fr;
  }

  .tab-list {
    flex-direction: row;
    overflow-x: auto;
    padding-bottom: 0.5rem;
    scrollbar-width: none;
  }

  .tab-list::-webkit-scrollbar {
    display: none;
  }

  .tab-button {
    min-width: 220px;
  }

  .tab-button:hover,
  .tab-button--active {
    transform: translateY(-2px);
  }
}

@media (max-width: 640px) {
  .tab-panel {
    grid-template-columns: 1fr;
    min-height: 0;
  }

  .tab-panel__media {
    min-height: 230px;
    max-height: 280px;
  }
}
</style>

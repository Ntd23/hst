<template>
  <section class="stats-section">
    <div class="stats-section__mesh stats-section__mesh--left" />
    <div class="stats-section__mesh stats-section__mesh--right" />

    <UContainer>
      <div class="stats-shell">
        <header class="stats-head">
          <div v-if="sectionData?.subtitle" class="stats-head__eyebrow">
            {{ sectionData.subtitle }}
          </div>

          <div class="stats-head__row">
            <div class="stats-head__content">
              <h2 v-if="sectionData?.title" class="stats-head__title">
                {{ sectionData.title }}
              </h2>
              <p
                v-if="sectionData?.description"
                class="stats-head__description"
              >
                {{ sectionData.description }}
              </p>
            </div>

            <div
              v-if="sectionData?.button?.label && sectionData?.button?.url"
              class="stats-head__cta"
            >
              <UButton
                :to="sectionData.button.url"
                color="primary"
                variant="solid"
                size="lg"
                class="stats-head__button"
              >
                {{ sectionData.button.label }}
              </UButton>
            </div>
          </div>
        </header>

        <div class="stats-grid">
          <article
            v-for="(tab, index) in tabs"
            :key="index"
            class="stats-card"
          >
            <div class="stats-card__glow" />

            <div class="stats-card__visual">
              <NuxtImg
                v-if="tab.image"
                :src="tab.image"
                :alt="tab.title"
                width="176"
                height="176"
                loading="lazy"
                class="stats-card__image"
              />
              <div v-else class="stats-card__fallback">
                <UIcon name="i-lucide-shield-check" class="stats-card__fallback-icon" />
              </div>
            </div>

            <div class="stats-card__body">
              <div class="stats-card__value">
                <span class="stats-card__number">
                  {{ statisticValues[index] ?? 0 }}
                </span>
                <span v-if="tab.unit" class="stats-card__unit">
                  {{ tab.unit }}
                </span>
              </div>

              <h3 class="stats-card__title">
                {{ tab.title }}
              </h3>
            </div>
          </article>
        </div>
      </div>
    </UContainer>
  </section>
</template>

<script setup lang="ts">
const props = defineProps<{
  data?: any;
}>();

const { sectionData, tabs, statisticValues } = useSiteStatisticsShortcode(
  toRef(props, "data")
);
</script>

<style scoped>
.stats-section {
  position: relative;
  overflow: hidden;
  isolation: isolate;
}

.stats-section__mesh {
  position: absolute;
  border-radius: 999px;
  pointer-events: none;
  z-index: 0;
}

.stats-section__mesh--left {
  top: 4rem;
  left: -8rem;
  width: 22rem;
  height: 22rem;
}

.stats-section__mesh--right {
  right: -10rem;
  bottom: 3rem;
  width: 26rem;
  height: 26rem;
}

.stats-shell {
  position: relative;
  z-index: 1;
  display: flex;
  flex-direction: column;
  gap: clamp(2.25rem, 4vw, 3.25rem);
}

.stats-head {
  display: flex;
  flex-direction: column;
  gap: 1.35rem;
}

.stats-head__eyebrow {
  display: inline-flex;
  align-self: flex-start;
  min-height: 2.5rem;
  align-items: center;
  padding: 0.55rem 1rem;
  border-radius: 999px;
  border: 1px solid rgba(255, 255, 255, 0.9);
  font-family: var(--font-tech, "Monda", sans-serif);
  font-size: 0.74rem;
  font-weight: 800;
  letter-spacing: 0.16em;
  text-transform: uppercase;
  color: #0284c7;
}

.stats-head__row {
  display: flex;
  flex-direction: column;
  gap: 1.75rem;
}

.stats-head__content {
  max-width: 48rem;
}

.stats-head__title {
  margin: 0;
  font-family: var(--font-tech, "Monda", sans-serif);
  font-size: clamp(2.75rem, 5vw, 4.85rem);
  font-weight: 800;
  line-height: 0.98;
  letter-spacing: -0.06em;
  color: #0f172a;
  text-wrap: balance;
}

.stats-head__description {
  margin: 1.4rem 0 0;
  max-width: 40rem;
  color: #64748b;
  font-size: 1rem;
  line-height: 1.85;
}

.stats-head__button {
  border-radius: 999px;
  padding-inline: 2rem;
  font-weight: 700;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(1, minmax(0, 1fr));
  gap: 1.9rem 1.1rem;
  padding-top: 0.25rem;
}

.stats-card {
  position: relative;
  display: flex;
  flex-direction: column;
  min-height: 13rem;
  padding: 0 1.1rem 1rem;
  border-radius: 2.25rem;
  border: 1px solid rgba(255, 255, 255, 0.9);
}

.stats-card__glow {
  position: absolute;
  top: 0.5rem;
  left: 24%;
  width: 52%;
  height: 4.5rem;
  border-radius: 999px;
  background: radial-gradient(circle, rgba(125, 211, 252, 0.28), rgba(125, 211, 252, 0));
  filter: blur(20px);
  pointer-events: none;
}

.stats-card__visual {
  position: relative;
  z-index: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 4.2rem;
  margin-top: -1rem;
  margin-bottom: 0.3rem;
}

.stats-card__image {
  width: min(5.2rem, 22vw);
  height: min(5.2rem, 22vw);
  object-fit: contain;
  filter: drop-shadow(0 14px 18px rgba(37, 99, 235, 0.12));
}

.stats-card__fallback {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 4.25rem;
  height: 4.25rem;
  border-radius: 999px;
  background: radial-gradient(circle at 30% 30%, #67e8f9, #3b82f6 68%, #4338ca);
  box-shadow:
    0 14px 24px rgba(37, 99, 235, 0.16),
    inset 0 1px 0 rgba(255, 255, 255, 0.48);
}

.stats-card__fallback-icon {
  width: 1.7rem;
  height: 1.7rem;
  color: white;
}

.stats-card__body {
  display: flex;
  flex: 1;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 0.3rem;
  padding-top: 0;
}

.stats-card__value {
  display: flex;
  align-items: baseline;
  justify-content: center;
  gap: 0.16rem;
}

.stats-card__number,
.stats-card__unit {
  font-family: var(--font-tech, "Monda", sans-serif);
  font-weight: 800;
  line-height: 0.9;
  letter-spacing: -0.07em;
}

.stats-card__number {
  font-size: clamp(2.4rem, 5vw, 3.6rem);
  color: #0f172a;
}

.stats-card__unit {
  font-size: clamp(1.05rem, 2vw, 1.6rem);
  color: #06b6d4;
}

.stats-card__title {
  margin: 0;
  max-width: 18ch;
  align-self: center;
  text-align: center;
  color: #334155;
  font-size: 0.92rem;
  font-weight: 500;
  line-height: 1.42;
  text-wrap: balance;
}

@media (max-width: 767px) {
  .stats-shell {
    gap: 1.5rem;
  }

  .stats-grid {
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 0.4rem;
    padding-top: 0.75rem;
  }

  .stats-card {
    min-width: 0;
    min-height: 8.5rem;
    padding: 0 0.2rem 0.65rem;
    border-radius: 1.15rem;
  }

  .stats-card__glow {
    top: 0.25rem;
    left: 10%;
    width: 80%;
    height: 2.75rem;
    filter: blur(12px);
  }

  .stats-card__visual {
    min-height: 2.9rem;
    margin-top: -0.75rem;
    margin-bottom: 0.15rem;
  }

  .stats-card__image {
    width: clamp(2.35rem, 10.5vw, 3.1rem);
    height: clamp(2.35rem, 10.5vw, 3.1rem);
    filter: drop-shadow(0 7px 10px rgba(37, 99, 235, 0.1));
  }

  .stats-card__fallback {
    width: clamp(2.35rem, 10.5vw, 3.1rem);
    height: clamp(2.35rem, 10.5vw, 3.1rem);
  }

  .stats-card__fallback-icon {
    width: 1.15rem;
    height: 1.15rem;
  }

  .stats-card__body {
    justify-content: flex-start;
    gap: 0.3rem;
  }

  .stats-card__number {
    font-size: clamp(1.35rem, 6vw, 1.75rem);
  }

  .stats-card__unit {
    font-size: clamp(0.62rem, 2.8vw, 0.8rem);
  }

  .stats-card__title {
    width: 100%;
    max-width: none;
    overflow-wrap: anywhere;
    font-size: clamp(0.56rem, 2.45vw, 0.68rem);
    line-height: 1.28;
  }
}

@media (min-width: 768px) {
  .stats-head__row {
    flex-direction: row;
    align-items: end;
    justify-content: space-between;
    gap: 2rem;
  }

  .stats-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 2.2rem 1.25rem;
  }

  .stats-card {
    min-height: 13.75rem;
    padding-inline: 1.2rem;
  }
}

@media (min-width: 1200px) {
  .stats-grid {
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 2.2rem 1.3rem;
  }

  .stats-card {
    min-height: 14rem;
    padding-inline: 1.2rem;
  }

  .stats-card__title {
    max-width: 20ch;
    font-size: 0.96rem;
  }
}
</style>

<template>
  <div
    v-if="title || subtitle || description"
    :class="[
      'section-heading',
      align === 'left' ? 'section-heading--left' : 'section-heading--center',
      compact ? 'section-heading--compact' : '',
    ]"
  >
    <div v-if="subtitle" class="section-heading__kicker-wrap">
      <span class="section-heading__kicker-dot" />
      <span class="section-heading__kicker" v-html="subtitle" />
    </div>

    <h2
      v-if="title"
      class="section-heading__title"
      v-html="title"
    />

    <p
      v-if="description"
      class="section-heading__description"
      v-html="description"
    />
  </div>
</template>

<script setup lang="ts">
withDefaults(
  defineProps<{
    title?: string | null;
    subtitle?: string | null;
    description?: string | null;
    align?: "left" | "center";
    compact?: boolean;
  }>(),
  {
    title: "",
    subtitle: "",
    description: "",
    align: "center",
    compact: false,
  }
);
</script>

<style scoped>
.section-heading {
  display: flex;
  flex-direction: column;
  gap: 0.95rem;
  margin-bottom: 2.5rem;
}

.section-heading--compact {
  gap: 0.8rem;
  margin-bottom: 2rem;
}

.section-heading--center {
  align-items: center;
  text-align: center;
}

.section-heading--left {
  align-items: flex-start;
  text-align: left;
}

.section-heading__kicker-wrap {
  display: inline-flex;
  align-items: center;
  gap: 0.55rem;
  padding: 0.45rem 0.95rem;
  border-radius: 999px;
  border: 1px solid rgba(14, 165, 233, 0.18);
  background: rgba(255, 255, 255, 0.84);
  box-shadow: 0 8px 24px rgba(15, 23, 42, 0.04);
}

.section-heading__kicker-dot {
  width: 0.42rem;
  height: 0.42rem;
  border-radius: 999px;
  background: linear-gradient(135deg, var(--color-primary), #34d399);
  box-shadow: 0 0 0 0.18rem rgba(14, 165, 233, 0.1);
}

.section-heading__kicker {
  font-family: var(--font-tech, sans-serif);
  color: #0369a1;
  font-size: 0.72rem;
  font-weight: 800;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  line-height: 1;
}

.section-heading__title {
  margin: 0;
  font-family: var(--font-tech, sans-serif);
  color: #0f172a;
  font-size: clamp(2rem, 4vw, 3.5rem);
  font-weight: 800;
  line-height: 1.12;
  letter-spacing: -0.04em;
}

.section-heading--left .section-heading__title {
  max-width: 18ch;
}

.section-heading__description {
  margin: 0;
  max-width: 42rem;
  color: #64748b;
  font-size: 0.98rem;
  line-height: 1.75;
}

@media (max-width: 640px) {
  .section-heading {
    gap: 0.8rem;
    margin-bottom: 2rem;
  }

  .section-heading__description {
    font-size: 0.92rem;
    line-height: 1.65;
  }
}
</style>

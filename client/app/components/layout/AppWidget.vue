<template>
  <div
    v-if="pending || !isReady || !isBootReady"
    class="app-widget-footer-skeleton"
    aria-hidden="true"
  >
    <UContainer class="app-widget-footer-skeleton__inner">
      <div class="app-widget-footer-skeleton__hero" />
      <div class="app-widget-footer-skeleton__grid">
        <div
          v-for="index in 3"
          :key="`footer-skeleton-${index}`"
          class="app-widget-footer-skeleton__card"
        />
      </div>
    </UContainer>
  </div>

  <footer
    v-else
    class="app-widget-footer"
    :style="footerBackgroundStyle"
  >
    <div class="app-widget-footer__noise" aria-hidden="true" />
    <UContainer class="app-widget-footer__inner">
      <section
        v-if="newsletterContent"
        class="footer-card footer-card--newsletter"
      >
        <div class="footer-card__glow" aria-hidden="true" />
        <div class="footer-newsletter">
          <div class="footer-newsletter__copy">
            <p
              v-if="newsletterContent.subtitle"
              class="footer-kicker"
              v-html="newsletterContent.subtitle"
            />
            <h2
              v-if="newsletterContent.title"
              class="footer-newsletter__title"
              v-html="newsletterContent.title"
            />
            <p
              v-if="newsletterContent.description"
              class="footer-newsletter__description"
              v-html="newsletterContent.description"
            />
          </div>

          <form
            class="footer-newsletter__form"
            @submit.prevent="handleNewsletterSubmit"
          >
            <Transition name="footer-newsletter-fade" mode="out-in">
              <div
                v-if="newsletterSubmitSuccess"
                key="newsletter-success"
                class="footer-newsletter__success"
                role="status"
              >
                <div class="footer-newsletter__success-icon">
                  <UIcon name="solar:check-circle-bold" class="size-6" />
                </div>
                <div class="footer-newsletter__success-copy">
                  <p class="footer-newsletter__success-title">
                    {{ newsletterLabels.successTitle }}
                  </p>
                  <p class="footer-newsletter__success-message">
                    {{ newsletterSubmitSuccess }}
                  </p>
                </div>
                <button
                  type="button"
                  class="footer-newsletter__secondary"
                  @click="resetNewsletterForm"
                >
                  {{ newsletterLabels.retry }}
                </button>
              </div>

              <div v-else key="newsletter-form" class="footer-newsletter__form-shell">
                <div
                  class="footer-newsletter__field"
                  :class="{ 'footer-newsletter__field--error': newsletterSubmitError }"
                >
                  <input
                    v-model="newsletterEmail"
                    type="email"
                    class="footer-newsletter__input"
                    :placeholder="newsletterLabels.emailPlaceholder"
                    :disabled="newsletterStore.loading"
                    autocomplete="email"
                  />
                  <button
                    type="submit"
                    class="footer-newsletter__button"
                    :disabled="newsletterStore.loading"
                  >
                    <span
                      v-if="newsletterStore.loading"
                      class="footer-newsletter__button-inner"
                    >
                      <span class="footer-newsletter__spinner" />
                      <span>{{ newsletterLabels.submitting }}</span>
                    </span>
                    <span v-else>{{ newsletterLabels.subscribe }}</span>
                  </button>
                </div>

                <p
                  v-if="newsletterSubmitError"
                  class="footer-newsletter__error"
                  role="alert"
                >
                  {{ newsletterSubmitError }}
                </p>
              </div>
            </Transition>
          </form>
        </div>
      </section>

      <section class="footer-grid">
        <article
          v-for="(widget, index) in orderedContentWidgets"
          :key="`content-${widget.meta?.widget}-${widget.meta?.position ?? index}`"
          class="footer-card"
          :class="contentCardClass(widget.meta?.widget)"
        >
          <template v-if="widget.meta?.widget === 'galleries'">
            <div class="footer-card__header">
              <p class="footer-kicker">{{ widget.data?.title || "Gallery" }}</p>
              <p
                v-if="widget.data?.description"
                class="footer-card__subtle"
                v-html="widget.data.description"
              />
            </div>

            <div class="footer-gallery">
              <NuxtLink
                v-for="item in widget.data?.items || []"
                :key="item.id || item.image"
                :to="item.url || '#'"
                class="footer-gallery__item"
              >
                <NuxtImg
                  v-if="item.image"
                  :src="item.image"
                  :alt="item.name || 'Gallery image'"
                  class="footer-gallery__image"
                  loading="lazy"
                />
                <div v-else class="footer-gallery__placeholder" />
              </NuxtLink>
            </div>
          </template>

          <template v-else>
            <div class="footer-card__header">
              <h3 class="footer-card__title">
                {{ widget.data?.title || "Navigation" }}
              </h3>
            </div>

            <ul
              :class="
                widget.meta?.widget === 'core-simple-menu' && hasIconItems(widget.data?.items)
                  ? 'footer-contact-list'
                  : 'footer-menu-list'
              "
            >
              <li
                v-for="(item, itemIndex) in widget.data?.items || []"
                :key="`${item.label}-${itemIndex}`"
                :class="hasIconItems(widget.data?.items) ? 'footer-contact-list__item' : undefined"
              >
                <template v-if="hasIconItems(widget.data?.items)">
                  <span class="footer-contact-list__icon">
                    <img
                      v-if="item.icon_image"
                      :src="item.icon_image"
                      :alt="item.label || 'Icon'"
                      class="h-4 w-4 object-contain"
                    />
                    <UIcon
                      v-else
                      :name="iconName(item.icon)"
                      class="size-4"
                    />
                  </span>
                  <NuxtLink
                    :to="item.url || '#'"
                    :target="item.open_new_tab ? '_blank' : undefined"
                    class="footer-contact-list__link"
                  >
                    {{ item.label }}
                  </NuxtLink>
                </template>
                <template v-else>
                  <NuxtLink
                    :to="item.url || '#'"
                    :target="item.open_new_tab ? '_blank' : undefined"
                    class="footer-menu-list__link"
                  >
                    {{ item.label }}
                  </NuxtLink>
                </template>
              </li>
            </ul>
          </template>
        </article>
      </section>

      <section
        v-if="copyrightText || socials.length"
        class="footer-bottom"
      >
        <p class="footer-bottom__copyright">
          {{ copyrightText }}
        </p>
        <div v-if="socials.length" class="footer-socials footer-socials--bottom">
          <NuxtLink
            v-for="social in socials"
            :key="social.network"
            :to="social.url"
            target="_blank"
            rel="noreferrer"
            class="footer-socials__item"
          >
            <img
              v-if="social.icon_image"
              :src="social.icon_image"
              :alt="social.label || social.network || 'Social icon'"
              class="h-4 w-4 object-contain"
            />
            <UIcon v-else :name="iconName(social.icon)" class="size-4" />
          </NuxtLink>
        </div>
      </section>
    </UContainer>
  </footer>
</template>

<script setup lang="ts">
import { useAppWidget } from "~/composables/layout/useAppWidget";
import { iconName } from "~/utils/iconName";

const {
  pending,
  isReady,
  footerSettings,
  newsletterWidget,
  orderedContentWidgets,
  copyrightText,
  socials,
} = useAppWidget();
const { isBootReady } = useAppBoot();
const {
  newsletterStore,
  email: newsletterEmail,
  labels: newsletterLabels,
  submitSuccess: newsletterSubmitSuccess,
  submitError: newsletterSubmitError,
  handleSubmit: handleNewsletterSubmit,
  resetForm: resetNewsletterForm,
} = useNewsletterWidgetForm();

if (import.meta.dev) {
  watchEffect(() => {
    console.log("Rendering footer widgets:", {
      newsletter: newsletterWidget.value
        ? {
            widget: newsletterWidget.value.meta?.widget,
            position: newsletterWidget.value.meta?.position,
          }
        : null,
      content: orderedContentWidgets.value.map((widget, index) => ({
        widget: widget.meta?.widget,
        position: widget.meta?.position ?? index,
        hasData: Boolean(widget.data),
      })),
      bottom: {
        hasCopyright: Boolean(copyrightText.value),
        socialsCount: socials.value.length,
      },
    });
  });
}

const newsletterContent = computed(() => newsletterWidget.value?.data || null);

const footerBackgroundStyle = computed(() => {
  const backgroundImage = footerSettings.value?.background_image;

  return backgroundImage
    ? {
        backgroundImage: `linear-gradient(180deg, rgba(8, 12, 24, 0.96), rgba(8, 12, 24, 0.98)), url('${backgroundImage}')`,
      }
    : undefined;
});

const hasIconItems = (items?: Array<{ icon?: string }>) =>
  Boolean(items?.some((item) => item.icon));

const contentCardClass = (widgetType?: string) => {
  if (widgetType === "galleries") {
    return "footer-card--gallery";
  }

  if (widgetType === "core-simple-menu") {
    return "footer-card--menu";
  }

  return "";
};
</script>

<style scoped>
.app-widget-footer-skeleton {
  margin-top: 3rem;
  padding: 1.5rem 0 2.75rem;
}

.app-widget-footer-skeleton__inner {
  display: grid;
  gap: 1rem;
}

.app-widget-footer-skeleton__hero,
.app-widget-footer-skeleton__card {
  border-radius: 1.9rem;
  background: rgba(15, 23, 42, 0.08);
  animation: pulse 1.5s ease-in-out infinite;
}

.app-widget-footer-skeleton__hero {
  height: 11rem;
}

.app-widget-footer-skeleton__grid {
  display: grid;
  gap: 1rem;
}

.app-widget-footer-skeleton__card {
  height: 14rem;
}

.app-widget-footer {
  position: relative;
  overflow: hidden;
  margin-top: 3rem;
  padding: 1.5rem 0 2.75rem;
  background-color: #070b16;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
  color: #94a3b8;
}

.app-widget-footer__noise {
  position: absolute;
  inset: 0;
  pointer-events: none;
  opacity: 0.16;
  background-image: radial-gradient(circle at 1px 1px, rgba(148, 163, 184, 0.2) 1px, transparent 0);
  background-size: 18px 18px;
  mask-image: linear-gradient(180deg, rgba(0, 0, 0, 0.25), rgba(0, 0, 0, 0.85));
}

.app-widget-footer__inner {
  position: relative;
  z-index: 1;
  display: grid;
  gap: 1.25rem;
}

.footer-card {
  position: relative;
  overflow: hidden;
  border-radius: 1.9rem;
  border: 1px solid rgba(148, 163, 184, 0.14);
  background:
    linear-gradient(180deg, rgba(16, 23, 42, 0.92), rgba(10, 15, 30, 0.94));
  box-shadow:
    0 22px 50px rgba(2, 6, 23, 0.45),
    inset 0 1px 0 rgba(255, 255, 255, 0.04);
}

.footer-card__glow {
  position: absolute;
  inset: auto -10% -40% auto;
  width: 18rem;
  height: 18rem;
  border-radius: 999px;
  background: radial-gradient(circle, rgba(34, 211, 238, 0.2), transparent 68%);
  pointer-events: none;
}

.footer-card__header {
  margin-bottom: 1rem;
}

.footer-card__title,
.footer-kicker,
.footer-newsletter__title {
  font-family: var(--font-tech);
}

.footer-kicker {
  margin-bottom: 0.35rem;
  color: #22d3ee;
  font-size: 0.74rem;
  font-weight: 700;
  letter-spacing: 0.16em;
  text-transform: uppercase;
}

.footer-card__title {
  color: #f8fafc;
  font-size: 1.2rem;
  font-weight: 700;
  letter-spacing: -0.02em;
}

.footer-card__subtle {
  color: #64748b;
  font-size: 0.88rem;
}

.footer-newsletter {
  display: grid;
  gap: 1.4rem;
  padding: 1.65rem;
}

.footer-newsletter__title {
  color: #f8fafc;
  font-size: clamp(1.8rem, 3vw, 2.8rem);
  font-weight: 700;
  line-height: 1.04;
  letter-spacing: -0.04em;
}

.footer-newsletter__description {
  margin-top: 0.85rem;
  max-width: 42rem;
  color: #94a3b8;
  font-size: 1rem;
  line-height: 1.7;
}

.footer-newsletter__form {
  display: grid;
  gap: 0.75rem;
  align-self: center;
}

.footer-newsletter__form-shell {
  display: grid;
  gap: 0.7rem;
}

.footer-newsletter__field {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  min-height: 4rem;
  border: 1px solid rgba(148, 163, 184, 0.16);
  border-radius: 999px;
  background: rgba(15, 23, 42, 0.88);
  padding: 0.35rem;
  transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.footer-newsletter__field:focus-within {
  border-color: rgba(34, 211, 238, 0.32);
  box-shadow: 0 0 0 3px rgba(34, 211, 238, 0.12);
}

.footer-newsletter__field--error {
  border-color: rgba(248, 113, 113, 0.55);
  box-shadow: 0 0 0 1px rgba(248, 113, 113, 0.18);
}

.footer-newsletter__input {
  width: 100%;
  min-width: 0;
  min-height: 3.2rem;
  border: 0;
  border-radius: 999px;
  background: transparent;
  padding: 0 1rem 0 1.15rem;
  color: #f8fafc;
  outline: none;
}

.footer-newsletter__input:disabled {
  cursor: not-allowed;
  opacity: 0.78;
}

.footer-newsletter__input::placeholder {
  color: #64748b;
}

.footer-newsletter__button {
  flex: 0 0 auto;
  min-width: 8.75rem;
  min-height: 3.2rem;
  padding: 0 1.2rem;
  border: 1px solid rgba(255, 255, 255, 0.08);
  border-radius: 999px;
  background: linear-gradient(135deg, #22d3ee, #34d399);
  color: #052033;
  font-family: var(--font-tech);
  font-size: 0.95rem;
  font-weight: 700;
  letter-spacing: 0.01em;
  white-space: nowrap;
  box-shadow: 0 14px 30px rgba(34, 211, 238, 0.2);
  transition:
    transform 0.2s ease,
    filter 0.2s ease,
    box-shadow 0.2s ease;
}

.footer-newsletter__button:disabled {
  cursor: not-allowed;
  transform: none;
  filter: saturate(0.8);
  opacity: 0.9;
}

.footer-newsletter__button:hover {
  transform: translateY(-1px);
  filter: brightness(1.06);
  box-shadow: 0 18px 34px rgba(34, 211, 238, 0.28);
}

.footer-newsletter__button-inner {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.55rem;
}

.footer-newsletter__spinner {
  display: inline-block;
  width: 1rem;
  height: 1rem;
  border: 2px solid rgba(4, 18, 29, 0.22);
  border-top-color: rgba(4, 18, 29, 0.92);
  border-radius: 999px;
  animation: footerSpin 0.8s linear infinite;
}

.footer-newsletter__error {
  border-radius: 1.25rem;
  border: 1px solid rgba(248, 113, 113, 0.2);
  background:
    linear-gradient(90deg, rgba(127, 29, 29, 0.18), rgba(30, 41, 59, 0.18));
  padding: 0.85rem 1rem 0.9rem;
  color: #fecaca;
  font-size: 0.92rem;
  line-height: 1.55;
}

.footer-newsletter__success {
  display: grid;
  gap: 0.9rem;
  border-radius: 1.4rem;
  border: 1px solid rgba(52, 211, 153, 0.18);
  background:
    radial-gradient(circle at top right, rgba(34, 211, 238, 0.16), transparent 38%),
    linear-gradient(180deg, rgba(6, 78, 59, 0.65), rgba(8, 47, 73, 0.78));
  padding: 1.15rem 1.15rem 1.2rem;
}

.footer-newsletter__success-icon {
  display: inline-flex;
  height: 2.9rem;
  width: 2.9rem;
  align-items: center;
  justify-content: center;
  border-radius: 999px;
  background: rgba(255, 255, 255, 0.14);
  color: #6ee7b7;
}

.footer-newsletter__success-copy {
  display: grid;
  gap: 0.3rem;
}

.footer-newsletter__success-title {
  color: #f8fafc;
  font-weight: 700;
  letter-spacing: -0.02em;
}

.footer-newsletter__success-message {
  color: #cbd5e1;
  font-size: 0.94rem;
  line-height: 1.6;
}

.footer-newsletter__secondary {
  width: fit-content;
  border: 1px solid rgba(255, 255, 255, 0.16);
  border-radius: 999px;
  background: rgba(255, 255, 255, 0.08);
  padding: 0.72rem 1rem;
  color: #f8fafc;
  font-size: 0.9rem;
  font-weight: 600;
  transition: background 0.2s ease, border-color 0.2s ease;
}

.footer-newsletter__secondary:hover {
  background: rgba(255, 255, 255, 0.12);
  border-color: rgba(255, 255, 255, 0.26);
}

.footer-grid {
  display: grid;
  gap: 1rem;
}

.footer-card--gallery,
.footer-card--menu {
  padding: 1.35rem;
}

.footer-gallery {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 0.75rem;
}

.footer-gallery__item {
  display: block;
  overflow: hidden;
  border-radius: 1rem;
  border: 1px solid rgba(148, 163, 184, 0.12);
  background: rgba(15, 23, 42, 0.72);
}

.footer-gallery__image,
.footer-gallery__placeholder {
  aspect-ratio: 1 / 1;
  width: 100%;
}

.footer-gallery__image {
  object-fit: cover;
}

.footer-gallery__placeholder {
  background: rgba(30, 41, 59, 0.75);
}

.footer-contact-list {
  display: grid;
  gap: 0.95rem;
}

.footer-contact-list__item {
  display: flex;
  align-items: flex-start;
  gap: 0.85rem;
}

.footer-contact-list__icon {
  display: inline-flex;
  flex: 0 0 auto;
  align-items: center;
  justify-content: center;
  width: 2rem;
  height: 2rem;
  border-radius: 999px;
  background: rgba(10, 18, 37, 0.9);
  color: #22d3ee;
}

.footer-contact-list__link {
  color: #cbd5e1;
  line-height: 1.6;
  transition: color 0.2s ease;
}

.footer-contact-list__link:hover {
  color: #22d3ee;
}

.footer-socials {
  display: flex;
  flex-wrap: wrap;
  gap: 0.75rem;
}

.footer-socials__item {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 2.8rem;
  height: 2.8rem;
  border-radius: 999px;
  border: 1px solid rgba(148, 163, 184, 0.12);
  background: rgba(10, 18, 37, 0.88);
  color: #22d3ee;
  transition: transform 0.2s ease, border-color 0.2s ease;
}

.footer-socials__item:hover {
  border-color: rgba(34, 211, 238, 0.35);
}

.footer-menu-list {
  display: grid;
  gap: 0.75rem;
}

.footer-menu-list__link {
  color: #94a3b8;
  line-height: 1.6;
  transition: color 0.2s ease;
}

.footer-menu-list__link:hover {
  color: #f8fafc;
}

.footer-bottom {
  padding-top: 0.75rem;
  display: flex;
  flex-direction: column;
  gap: 0.9rem;
}

.footer-bottom__copyright {
  color: #64748b;
  font-size: 0.82rem;
  line-height: 1.6;
}

.footer-socials--bottom {
  justify-content: flex-start;
}

@media (min-width: 768px) {
  .app-widget-footer-skeleton__grid {
    grid-template-columns: repeat(3, minmax(0, 1fr));
  }

  .app-widget-footer {
    padding-top: 2rem;
    padding-bottom: 3.5rem;
  }

  .footer-newsletter {
    grid-template-columns: minmax(0, 1.3fr) minmax(20rem, 0.9fr);
    align-items: center;
    padding: 2rem;
  }

  .footer-newsletter__field {
    min-height: 4.2rem;
    padding: 0.4rem;
  }

  .footer-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
    align-items: stretch;
  }

  .footer-gallery {
    grid-template-columns: repeat(3, minmax(0, 1fr));
  }

  .footer-bottom {
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
  }
}

@media (min-width: 1280px) {
  .app-widget-footer__inner {
    gap: 1.5rem;
  }

  .footer-grid {
    grid-template-columns: repeat(3, minmax(0, 1fr));
  }
}

@keyframes pulse {
  0%,
  100% {
    opacity: 0.55;
  }

  50% {
    opacity: 1;
  }
}

.footer-newsletter-fade-enter-active,
.footer-newsletter-fade-leave-active {
  transition: opacity 0.24s ease, transform 0.24s ease;
}

.footer-newsletter-fade-enter-from,
.footer-newsletter-fade-leave-to {
  opacity: 0;
  transform: translateY(8px);
}

@keyframes footerSpin {
  to {
    transform: rotate(360deg);
  }
}
</style>

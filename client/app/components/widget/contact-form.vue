<template>
  <div
    ref="contactPanelRef"
    class="relative overflow-hidden rounded-2xl border border-slate-100 p-6 shadow-sm"
    :style="{ background: widgetBackground }"
  >
    <Transition name="contact-fade">
      <div
        v-if="contactStore.loading"
        class="contact-loading-overlay"
        aria-hidden="true"
      >
        <div class="contact-loading-overlay__pulse" />
        <div class="contact-loading-overlay__badge">
          <span class="contact-spinner" />
          <span>{{ labels.submitting }}</span>
        </div>
      </div>
    </Transition>

    <h4 v-if="formMeta.title" class="mb-4 text-base font-black tracking-tight text-slate-900">
      {{ formMeta.title }}
    </h4>

    <Transition name="contact-fade" mode="out-in">
      <div v-if="submitSuccess" key="success" class="space-y-3" role="status">
        <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-500 text-white">
          <UIcon name="solar:check-circle-bold" class="size-7" />
        </div>
        <div class="space-y-2">
          <h5 class="text-lg font-bold text-slate-900">
            {{ successState.title }}
          </h5>
          <p class="text-sm leading-relaxed text-slate-600">
            {{ submitSuccess }}
          </p>
        </div>
        <button class="contact-secondary-btn" type="button" @click="handleSendAnother">
          {{ successState.action }}
        </button>
      </div>

      <form v-else key="form" class="space-y-3" novalidate @submit.prevent="handleSubmit">
        <div>
          <input
            id="service-contact-name"
            ref="nameInputRef"
            v-model="form.name"
            :aria-describedby="fieldError('name') ? fieldErrorId('name') : undefined"
            :aria-invalid="fieldError('name') ? 'true' : 'false'"
            :placeholder="nameLabel"
            autocomplete="name"
            class="contact-input"
            type="text"
          />
          <p
            v-if="fieldError('name')"
            :id="fieldErrorId('name')"
            class="contact-error"
          >
            {{ fieldError("name") }}
          </p>
        </div>

        <div>
          <input
            id="service-contact-email"
            ref="emailInputRef"
            v-model="form.email"
            :aria-describedby="fieldError('email') ? fieldErrorId('email') : undefined"
            :aria-invalid="fieldError('email') ? 'true' : 'false'"
            :placeholder="emailLabel"
            autocomplete="email"
            class="contact-input"
            type="email"
          />
          <p
            v-if="fieldError('email')"
            :id="fieldErrorId('email')"
            class="contact-error"
          >
            {{ fieldError("email") }}
          </p>
        </div>

        <div>
          <textarea
            id="service-contact-message"
            ref="contentInputRef"
            v-model="form.content"
            :aria-describedby="fieldError('content') ? fieldErrorId('content') : undefined"
            :aria-invalid="fieldError('content') ? 'true' : 'false'"
            :placeholder="messageLabel"
            autocomplete="off"
            class="contact-input resize-none"
            rows="4"
          />
          <p
            v-if="fieldError('content')"
            :id="fieldErrorId('content')"
            class="contact-error"
          >
            {{ fieldError("content") }}
          </p>
        </div>

        <p
          v-if="submitError"
          role="alert"
          class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600"
        >
          {{ submitError }}
        </p>

        <button
          :disabled="contactStore.loading"
          class="btn-shared-cta w-full"
          type="submit"
        >
          <span v-if="contactStore.loading" class="contact-submit-btn__inner">
            <span class="contact-spinner contact-spinner--button" />
            <span>{{ labels.submitting }}</span>
          </span>
          <span v-else>{{ formMeta.buttonLabel }}</span>
        </button>
      </form>
    </Transition>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{ data?: any }>();
const { translate, localeCode } = useI18nText();

const widgetSource = computed(() => ({
  title: props.data?.title || "",
  form_title: props.data?.title || "",
  form_description: props.data?.description || "",
  form_button_label:
    props.data?.button_label ||
    translate("contactForm.submit", localeCode.value === "en" ? "Send" : "Gửi"),
  display_fields: "email",
  mandatory_fields: "email",
  display_policy: false,
}));

const {
  contactStore,
  contactPanelRef,
  nameInputRef,
  emailInputRef,
  contentInputRef,
  formMeta,
  labels,
  successState,
  form,
  submitError,
  submitSuccess,
  fieldError,
  fieldErrorId,
  handleSubmit,
  handleSendAnother,
} = useContactSectionForm(widgetSource);

const widgetBackground = computed(
  () => props.data?.background_color || "#ECF6FA"
);
const nameLabel = computed(() =>
  translate(
    "contactForm.name",
    localeCode.value === "en" ? "Name" : "Họ và tên"
  )
);
const emailLabel = computed(() =>
  translate("contactForm.email", localeCode.value === "en" ? "Email" : "Email")
);
const messageLabel = computed(() =>
  translate(
    "contactForm.messagePlaceholder",
    localeCode.value === "en"
      ? "Write your message here."
      : "Viết lời nhắn của bạn ở đây."
  )
);
</script>

<style scoped>
.contact-input {
  width: 100%;
  border-radius: 0.75rem;
  border: 1px solid rgb(226 232 240);
  background: rgb(255 255 255 / 0.92);
  padding: 0.75rem 1rem;
  color: rgb(15 23 42);
}

.contact-input::placeholder {
  color: rgb(148 163 184);
}

.contact-input:focus {
  outline: none;
  border-color: transparent;
  box-shadow: 0 0 0 2px rgb(0 124 195 / 0.35);
}

.contact-input[aria-invalid="true"] {
  border-color: rgb(248 113 113);
  box-shadow: 0 0 0 1px rgb(248 113 113 / 0.18);
}

.contact-error {
  margin-top: 0.375rem;
  font-size: 0.875rem;
  color: rgb(220 38 38);
}

.contact-loading-overlay {
  position: absolute;
  inset: 0;
  z-index: 5;
  display: flex;
  align-items: flex-start;
  justify-content: center;
  padding: 1.5rem;
  background: rgb(236 246 250 / 0.48);
  backdrop-filter: blur(4px);
}

.contact-loading-overlay__pulse {
  position: absolute;
  inset: 0;
  background: linear-gradient(
    120deg,
    transparent 0%,
    rgb(255 255 255 / 0.2) 35%,
    rgb(255 255 255 / 0.45) 50%,
    transparent 65%
  );
  transform: translateX(-100%);
  animation: contactShimmer 1.4s linear infinite;
}

.contact-loading-overlay__badge {
  position: relative;
  z-index: 1;
  display: inline-flex;
  align-items: center;
  gap: 0.75rem;
  border-radius: 999px;
  border: 1px solid rgb(191 219 254 / 0.9);
  background: rgb(255 255 255 / 0.95);
  padding: 0.8rem 1rem;
  font-size: 0.95rem;
  font-weight: 700;
  color: rgb(15 23 42);
  box-shadow: 0 20px 60px rgb(14 116 144 / 0.16);
}

.contact-spinner {
  display: inline-block;
  height: 1.1rem;
  width: 1.1rem;
  border: 2px solid rgb(14 116 144 / 0.18);
  border-top-color: rgb(2 132 199);
  border-radius: 999px;
  animation: contactSpin 0.8s linear infinite;
}

.contact-spinner--button {
  border-color: rgb(255 255 255 / 0.25);
  border-top-color: rgb(255 255 255);
}

.contact-submit-btn__inner {
  display: inline-flex;
  align-items: center;
  gap: 0.625rem;
}

.contact-secondary-btn {
  display: inline-flex;
  width: fit-content;
  align-items: center;
  justify-content: center;
  border-radius: 999px;
  border: 1px solid rgb(125 211 252);
  background: rgb(255 255 255 / 0.82);
  padding: 0.75rem 1rem;
  font-size: 0.9rem;
  font-weight: 600;
  color: rgb(3 105 161);
}

.contact-fade-enter-active,
.contact-fade-leave-active {
  transition: opacity 0.24s ease, transform 0.24s ease;
}

.contact-fade-enter-from,
.contact-fade-leave-to {
  opacity: 0;
  transform: translateY(8px);
}

@keyframes contactSpin {
  to {
    transform: rotate(360deg);
  }
}

@keyframes contactShimmer {
  to {
    transform: translateX(100%);
  }
}
</style>

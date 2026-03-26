<template>
  <main class="container px-4 pb-20">
    <div class="mx-auto">
      <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
        <div class="space-y-8 lg:col-span-5">
          <div class="glass-panel h-full rounded-2xl p-8">
            <h2 class="mb-6 text-2xl font-bold text-slate-900">
              {{ sectionData.title }}
            </h2>
            <p class="mb-8 leading-relaxed text-slate-600">
              {{ sectionData.description }}
            </p>

            <div class="space-y-6">
              <div
                v-for="(item, index) in informationItems"
                :key="`${item.title}-${index}`"
                class="flex items-start gap-4"
              >
                <div
                  class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-blue-50 text-primary"
                >
                  <UIcon :name="item.icon" class="size-7" />
                </div>
                <div>
                  <h3 class="text-lg font-bold text-slate-900">
                    {{ item.title }}
                  </h3>
                  <p class="mt-1 text-sm leading-relaxed text-slate-600">
                    {{ item.description }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="lg:col-span-7">
          <div
            ref="contactPanelRef"
            :class="{ 'contact-panel--submitting': contactStore.loading }"
            class="glass-panel contact-panel relative h-full rounded-2xl p-8"
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

            <h2 class="mb-2 text-2xl font-bold text-slate-900">
              {{ formMeta.title }}
            </h2>
            <p class="mb-8 text-sm text-slate-500">
              {{ formMeta.description }}
            </p>

            <Transition name="contact-fade" mode="out-in">
              <div
                v-if="submitSuccess"
                key="success"
                class="contact-success-state"
                role="status"
              >
                <div class="contact-success-state__icon">
                  <UIcon name="solar:check-circle-bold" class="size-9" />
                </div>
                <div class="space-y-2">
                  <h3 class="text-2xl font-bold text-slate-900">
                    {{ successState.title }}
                  </h3>
                  <p class="text-sm leading-relaxed text-slate-600">
                    {{ submitSuccess }}
                  </p>
                  <p class="text-sm leading-relaxed text-slate-500">
                    {{ successState.description }}
                  </p>
                </div>
                <button
                  class="contact-secondary-btn"
                  type="button"
                  @click="handleSendAnother"
                >
                  {{ successState.action }}
                </button>
              </div>

              <form
                v-else
                key="form"
                :aria-busy="contactStore.loading"
                class="space-y-5"
                @submit.prevent="handleSubmit"
              >
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                  <div>
                    <label for="contact-name">
                      {{ labels.name }}
                      <span v-if="mandatoryFields.name" class="text-red-500"
                        >*</span
                      >
                    </label>
                    <input
                      id="contact-name"
                      ref="nameInputRef"
                      v-model="form.name"
                      :placeholder="`${labels.name}....`"
                      class="contact-input"
                      required
                      type="text"
                    />
                    <p v-if="fieldError('name')" class="contact-error">
                      {{ fieldError("name") }}
                    </p>
                  </div>

                  <div v-if="displayFields.email">
                    <label for="contact-email">
                      {{ labels.email }}
                      <span v-if="mandatoryFields.email" class="text-red-500"
                        >*</span
                      >
                    </label>
                    <input
                      id="contact-email"
                      v-model="form.email"
                      :placeholder="`${labels.email}....`"
                      :required="mandatoryFields.email"
                      class="contact-input"
                      type="email"
                    />
                    <p v-if="fieldError('email')" class="contact-error">
                      {{ fieldError("email") }}
                    </p>
                  </div>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                  <div v-if="displayFields.address">
                    <label for="contact-address">
                      {{ labels.address }}
                      <span v-if="mandatoryFields.address" class="text-red-500"
                        >*</span
                      >
                    </label>
                    <input
                      id="contact-address"
                      v-model="form.address"
                      :placeholder="`${labels.address}....`"
                      :required="mandatoryFields.address"
                      class="contact-input"
                      type="text"
                    />
                    <p v-if="fieldError('address')" class="contact-error">
                      {{ fieldError("address") }}
                    </p>
                  </div>

                  <div v-if="displayFields.phone">
                    <label for="contact-phone">
                      {{ labels.phone }}
                      <span v-if="mandatoryFields.phone" class="text-red-500"
                        >*</span
                      >
                    </label>
                    <input
                      id="contact-phone"
                      v-model="form.phone"
                      :placeholder="`${labels.phone}....`"
                      :required="mandatoryFields.phone"
                      class="contact-input"
                      type="tel"
                    />
                    <p v-if="fieldError('phone')" class="contact-error">
                      {{ fieldError("phone") }}
                    </p>
                  </div>
                </div>

                <div v-if="displayFields.subject">
                  <label for="contact-subject">
                    {{ labels.subject }}
                    <span v-if="mandatoryFields.subject" class="text-red-500"
                      >*</span
                    >
                  </label>
                  <input
                    id="contact-subject"
                    v-model="form.subject"
                    :placeholder="`${labels.subject}....`"
                    :required="mandatoryFields.subject"
                    class="contact-input"
                    type="text"
                  />
                  <p v-if="fieldError('subject')" class="contact-error">
                    {{ fieldError("subject") }}
                  </p>
                </div>

                <div>
                  <label class="sr-only" for="contact-message">
                    {{ labels.message }}
                  </label>
                  <textarea
                    id="contact-message"
                    v-model="form.content"
                    :placeholder="labels.message"
                    class="contact-input resize-none"
                    required
                    rows="4"
                  />
                  <p v-if="fieldError('content')" class="contact-error">
                    {{ fieldError("content") }}
                  </p>
                </div>

                <div class="flex items-start gap-2 pt-2">
                  <input
                    id="policy"
                    v-model="form.agree_terms_and_policy"
                    class="mt-1 h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary"
                    type="checkbox"
                  />
                  <label class="text-sm text-slate-600" for="policy">
                    {{ labels.policy }}
                  </label>
                </div>
                <p
                  v-if="fieldError('agree_terms_and_policy')"
                  class="contact-error"
                >
                  {{ fieldError("agree_terms_and_policy") }}
                </p>

                <p
                  v-if="submitError"
                  class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600"
                >
                  {{ submitError }}
                </p>

                <button
                  :disabled="contactStore.loading"
                  class="contact-submit-btn"
                  type="submit"
                >
                  <span
                    v-if="contactStore.loading"
                    class="contact-submit-btn__inner"
                  >
                    <span class="contact-spinner contact-spinner--button" />
                    <span>{{ labels.submitting }}</span>
                  </span>
                  <span v-else>{{ formMeta.buttonLabel }}</span>
                </button>
              </form>
            </Transition>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script setup lang="ts">
const props = defineProps<{
  data?: any;
}>();

const {
  contactStore,
  contactPanelRef,
  nameInputRef,
  sectionData,
  formMeta,
  labels,
  successState,
  informationItems,
  displayFields,
  mandatoryFields,
  form,
  submitError,
  submitSuccess,
  fieldError,
  handleSubmit,
  handleSendAnother,
} = useContactSectionForm(toRef(props, "data"));
</script>

<style scoped>
.contact-input {
  width: 100%;
  border-radius: 0.75rem;
  border: 1px solid rgb(226 232 240);
  background: rgb(255 255 255 / 0.7);
  padding: 0.75rem 1rem;
  color: rgb(15 23 42);
  transition: all 0.2s ease;
}

.contact-input::placeholder {
  color: rgb(148 163 184);
}

.contact-input:focus {
  outline: none;
  border-color: transparent;
  box-shadow: 0 0 0 2px rgb(0 124 195 / 0.35);
}

.contact-error {
  margin-top: 0.375rem;
  font-size: 0.875rem;
  color: rgb(220 38 38);
}

.contact-panel {
  overflow: hidden;
}

.contact-panel--submitting > *:not(.contact-loading-overlay) {
  pointer-events: none;
}

.contact-loading-overlay {
  position: absolute;
  inset: 0;
  z-index: 5;
  display: flex;
  align-items: flex-start;
  justify-content: center;
  padding: 1.5rem;
  background: rgb(236 246 250 / 0.42);
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

.contact-submit-btn {
  display: inline-flex;
  min-height: 3rem;
  align-items: center;
  justify-content: center;
  border-radius: 0.75rem;
  background: linear-gradient(135deg, rgb(0 124 195), rgb(20 23 108));
  padding: 0.75rem 2rem;
  font-size: 0.875rem;
  font-weight: 700;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: white;
  box-shadow: 0 18px 40px rgb(37 99 235 / 0.28);
  transition: all 0.2s ease;
}

.contact-submit-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 24px 46px rgb(37 99 235 / 0.34);
}

.contact-submit-btn:disabled {
  cursor: not-allowed;
  opacity: 0.85;
}

.contact-submit-btn__inner {
  display: inline-flex;
  align-items: center;
  gap: 0.625rem;
}

.contact-success-state {
  display: grid;
  gap: 1rem;
  border-radius: 1.5rem;
  border: 1px solid rgb(167 243 208);
  background: radial-gradient(
      circle at top right,
      rgb(167 243 208 / 0.46),
      transparent 30%
    ),
    linear-gradient(180deg, rgb(240 253 250), rgb(236 253 245));
  padding: 1.75rem;
  box-shadow: 0 30px 70px rgb(16 185 129 / 0.12);
}

.contact-success-state__icon {
  display: inline-flex;
  height: 4rem;
  width: 4rem;
  align-items: center;
  justify-content: center;
  border-radius: 1.25rem;
  background: linear-gradient(135deg, rgb(16 185 129), rgb(5 150 105));
  color: white;
  box-shadow: 0 18px 36px rgb(16 185 129 / 0.25);
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
  transition: all 0.2s ease;
}

.contact-secondary-btn:hover {
  transform: translateY(-1px);
  border-color: rgb(56 189 248);
  background: white;
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

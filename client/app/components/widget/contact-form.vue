<template>
  <div
    class="rounded-2xl p-6"
    :style="{ background: content.background_color || '#ECF6FA' }"
  >
    <h4 v-if="content.title" class="mb-4 text-base font-black tracking-tight text-slate-900">
      {{ content.title }}
    </h4>

    <form class="space-y-3" @submit.prevent="handleSubmit">
      <input
        v-model="form.name"
        type="text"
        :placeholder="nameLabel"
        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-transparent focus:outline-none focus:ring-2 focus:ring-primary"
      />
      <input
        v-model="form.email"
        type="email"
        :placeholder="emailLabel"
        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-transparent focus:outline-none focus:ring-2 focus:ring-primary"
      />
      <textarea
        v-model="form.content"
        rows="4"
        :placeholder="messageLabel"
        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-transparent focus:outline-none focus:ring-2 focus:ring-primary"
      />

      <p v-if="submitError" class="text-sm text-red-500">{{ submitError }}</p>
      <p v-else-if="submitSuccess" class="text-sm text-emerald-600">{{ submitSuccess }}</p>

      <button
        type="submit"
        class="w-full rounded-xl bg-[#1a237e] py-3 text-sm font-bold uppercase tracking-wide text-white shadow-lg transition-colors hover:bg-blue-900 disabled:cursor-not-allowed disabled:opacity-60"
        :disabled="contactStore.loading"
      >
        {{ contactStore.loading ? submittingLabel : content.button_label || submitLabel }}
      </button>
    </form>
  </div>
</template>

<script setup lang="ts">
import type { ContactFormPayload } from "~~/shared/validation/types";

const props = defineProps<{ data?: any }>();
const { translate, localeCode } = useI18nText();
const contactStore = useContactStore();
const content = computed(() => props.data || {});

const form = reactive({
  name: "",
  email: "",
  content: "",
});

const submitError = ref("");
const submitSuccess = ref("");

const nameLabel = computed(() =>
  translate("contactForm.name", localeCode.value === "en" ? "Name" : "Họ và tên")
);
const emailLabel = computed(() =>
  translate("contactForm.email", localeCode.value === "en" ? "Email" : "Email")
);
const messageLabel = computed(() =>
  translate("contactForm.messagePlaceholder", localeCode.value === "en" ? "Write your message here." : "Viết lời nhắn của bạn ở đây.")
);
const submitLabel = computed(() =>
  translate("contactForm.submit", localeCode.value === "en" ? "Send" : "Gửi")
);
const submittingLabel = computed(() =>
  translate("contactForm.submitting", localeCode.value === "en" ? "Sending..." : "Đang gửi...")
);

const handleSubmit = async () => {
  submitError.value = "";
  submitSuccess.value = "";

  try {
    const payload: ContactFormPayload = {
      name: form.name,
      email: form.email || undefined,
      content: form.content,
      display_fields: "email",
      required_fields: "email",
    };

    const response = await contactStore.submitSectionForm(payload);
    submitSuccess.value = response?.message || "Send message successfully!";
    form.name = "";
    form.email = "";
    form.content = "";
  } catch (error: any) {
    submitError.value =
      error?.data?.message ||
      error?.statusMessage ||
      contactStore.submitError ||
      "Submit failed";
  }
};
</script>

<template>
  <main class="flex-grow pb-20 px-4 container">
    <div class="mx-auto">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-5 space-y-8">
          <div class="glass-panel p-8 rounded-2xl h-full">
            <h2 class="text-2xl font-bold text-slate-900 mb-6">
              {{ information.title }}
            </h2>
            <p class="text-slate-600 mb-8 leading-relaxed">
              {{ information.description }}
            </p>
            <div class="space-y-6">
              <div class="flex items-start gap-4">
                <div
                  class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center shrink-0 text-primary"
                >
                  <UIcon name="solar:map-point-bold" class="size-7" />
                </div>
                <div>
                  <h3 class="font-bold text-slate-900 text-lg">
                    {{ information.title1 }}
                  </h3>
                  <p class="text-slate-600 mt-1 text-sm leading-relaxed">
                    {{ information.description_1 }}
                  </p>
                </div>
              </div>
              <div class="flex items-start gap-4">
                <div
                  class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center shrink-0 text-primary"
                >
                  <UIcon name="solar:phone-bold" class="size-7" />
                </div>
                <div>
                  <h3 class="font-bold text-slate-900 text-lg">
                    {{ information.title2 }}
                  </h3>
                  <p class="text-slate-600 mt-1 font-medium">
                    {{ information.description_2 }}
                  </p>
                </div>
              </div>
              <div class="flex items-start gap-4">
                <div
                  class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center shrink-0 text-primary"
                >
                  <UIcon name="solar:letter-bold" class="size-7" />
                </div>
                <div>
                  <h3 class="font-bold text-slate-900 text-lg">
                    {{ information.title3 }}
                  </h3>
                  <p class="text-slate-600 mt-1 font-medium">
                    {{ information.description_3 }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="lg:col-span-7">
          <div class="glass-panel p-8 rounded-2xl h-full">
            <h2 class="text-2xl font-bold text-slate-900 mb-2">
              {{ formRight.form_title }}
            </h2>
            <p class="text-slate-500 text-sm mb-8">
              {{ formRight.form_description }}
            </p>
            <form class="space-y-5">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div v-if="displayFields.subject">
                  <label class="" for="name"
                    >{{ formRight.name }}
                    <span v-if="mandatoryFields.subject" class="text-red-500"
                      >*</span
                    ></label
                  >
                  <input
                    :required="mandatoryFields.subject"
                    class="w-full px-4 py-3 rounded-xl bg-white/70 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-slate-900 placeholder-slate-400 transition-all"
                    id="name"
                    :placeholder="formRight.name + '....'"
                    type="text"
                  />
                </div>
                <div v-if="displayFields.email">
                  <label class="" for="email"
                    >{{ information.title3 }}
                    <span v-if="mandatoryFields.email" class="text-red-500"
                      >*</span
                    ></label
                  >
                  <input
                    :required="mandatoryFields.email"
                    class="w-full px-4 py-3 rounded-xl bg-white/70 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-slate-900 placeholder-slate-400 transition-all"
                    id="email"
                    :placeholder="information.title3 + '....'"
                    type="email"
                  />
                </div>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div v-if="displayFields.address">
                  <label class="" for="address"
                    >{{ information.title1 }}
                    <span v-if="mandatoryFields.address" class="text-red-500"
                      >*</span
                    ></label
                  >
                  <input
                    :required="mandatoryFields.address"
                    class="w-full px-4 py-3 rounded-xl bg-white/70 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-slate-900 placeholder-slate-400 transition-all"
                    id="address"
                    :placeholder="information.title1 + '....'"
                    type="text"
                  />
                </div>
                <div v-if="displayFields.phone">
                  <label class="" for="phone"
                    >{{ information.title2 }}
                    <span v-if="mandatoryFields.phone" class="text-red-500"
                      >*</span
                    ></label
                  >
                  <input
                    :required="mandatoryFields.phone"
                    class="w-full px-4 py-3 rounded-xl bg-white/70 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-slate-900 placeholder-slate-400 transition-all"
                    id="phone"
                    :placeholder="information.title2 + '....'"
                    type="tel"
                  />
                </div>
              </div>
              <div>
                <label class="sr-only" for="subject">{{
                  formRight.title_form
                }}</label>
                <input
                  class="w-full px-4 py-3 rounded-xl bg-white/70 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-slate-900 placeholder-slate-400 transition-all"
                  id="subject"
                  :placeholder="formRight.title_form + '....'"
                  type="text"
                />
              </div>
              <div>
                <label class="sr-only" for="message">{{
                  formRight.description_form
                }}</label>
                <textarea
                  class="w-full px-4 py-3 rounded-xl bg-white/70 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-slate-900 placeholder-slate-400 transition-all resize-none"
                  id="message"
                  :placeholder="formRight.description_form"
                  rows="4"
                ></textarea>
              </div>
              <div class="flex items-start gap-2 pt-2">
                <input
                  class="mt-1 w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary"
                  id="policy"
                  type="checkbox"
                />
                <label class="text-sm text-slate-600" for="policy">{{
                  formRight.policy
                }}</label>
              </div>
              <button
                class="px-8 py-3 rounded-xl bg-primary text-white font-bold shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 hover:bg-opacity-90 transition-all transform hover:-translate-y-0.5 uppercase tracking-wide text-sm"
                type="submit"
              >
                {{ props.data.items.form_button_label }}
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script setup lang="ts">
import { ref } from "vue";

const props = defineProps<{
  data?: any;
}>();
const information = ref({
  title: props.data.items.title,
  title1: props.data.items.title_1,
  title2: props.data.items.title_2,
  title3: props.data.items.title_3,
  description: props.data.items.description,
  description_1: props.data.items.description_1,
  description_2: props.data.items.description_2,
  description_3: props.data.items.description_3,
});
const formRight = ref({
  form_title: props.data.items.form_title,
  form_description: props.data.items.form_description,
  name: props.data.locale == "en" ? "Name" : "Họ và Tên",
  policy:
    props.data.locale == "en"
      ? "I agree to the Terms and Privacy Policy."
      : "Tôi đồng ý với Điều khoản và Chính sách quyền riêng tư",
  title_form: props.data.locale == "en" ? "Title." : "Tiêu đề",
  description_form:
    props.data.locale == "en"
      ? "Write your message here."
      : "Viết lời nhắn của bạn ở đây.",
});
const displayFields = ref(
  (props.data.items.display_fields || "")
    .split(",")
    .filter(Boolean)
    .reduce((acc, field) => {
      acc[field] = true;
      return acc;
    }, {})
);
const mandatoryFields = ref(
  (props.data.items.mandatory_fields || "")
    .split(",")
    .filter(Boolean)
    .reduce((acc, field) => {
      acc[field] = true;
      return acc;
    }, {})
);
const formData = ref({
  form_title: props.data.items.form_title,
  form_description: props.data.items.form_description,
});

const sectionData = computed(() => props.data?.data || props.data || {});
</script>

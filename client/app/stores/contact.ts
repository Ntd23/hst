import type { ContactFormPayload } from "~~/shared/validation/types";

export const useContactStore = defineStore("contact", () => {
  const loading = ref(false);
  const errors = ref<Record<string, string[] | string | undefined>>({});
  const submitError = ref("");

  const submitSectionForm = async (payload: ContactFormPayload) => {
    loading.value = true;
    errors.value = {};
    submitError.value = "";

    try {
      const response = await $fetch<any>("/api/pages/contact/section/form", {
        method: "POST",
        body: payload,
      });

      return response;
    } catch (error: any) {
      const responseData = error?.data || error?.response?._data || {};

      errors.value = responseData?.errors || {};
      submitError.value = responseData?.message || error?.statusMessage || "Submit failed";

      throw error;
    } finally {
      loading.value = false;
    }
  };

  return {
    loading,
    errors,
    submitError,
    submitSectionForm,
  };
});


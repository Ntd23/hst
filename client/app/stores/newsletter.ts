export type NewsletterPayload = {
  email: string;
};

export const useNewsletterStore = defineStore("newsletter", () => {
  const loading = ref(false);
  const errors = ref<Record<string, string[] | string | undefined>>({});
  const submitError = ref("");

  const submitWidgetForm = async (payload: NewsletterPayload) => {
    loading.value = true;
    errors.value = {};
    submitError.value = "";

    try {
      const response = await $fetch<any>("/api/pages/subscribe/widget/form", {
        method: "POST",
        body: payload,
      });

      return response;
    } catch (error: any) {
      const responseData = error?.data || error?.response?._data || {};

      errors.value = responseData?.errors || {};
      submitError.value =
        responseData?.message || error?.statusMessage || "Subscribe failed";

      throw error;
    } finally {
      loading.value = false;
    }
  };

  return {
    loading,
    errors,
    submitError,
    submitWidgetForm,
  };
});

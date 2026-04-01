export const useNewsletterWidgetForm = () => {
  const { translate, localeCode } = useI18nText();
  const newsletterStore = useNewsletterStore();
  const email = ref("");
  const submitSuccess = ref("");
  const localError = ref("");

  const labels = computed(() => ({
    emailPlaceholder: translate(
      "footer.emailPlaceholder",
      localeCode.value === "en" ? "Your email" : "Email của bạn"
    ),
    subscribe: translate(
      "footer.subscribe",
      localeCode.value === "en" ? "Subscribe" : "Đăng ký"
    ),
    submitting: translate(
      "footer.submitting",
      localeCode.value === "en" ? "Submitting..." : "Đang đăng ký..."
    ),
    successTitle: translate(
      "footer.newsletterSuccessTitle",
      localeCode.value === "en" ? "Subscribed successfully" : "Đăng ký thành công"
    ),
    successMessage: translate(
      "footer.newsletterSuccessMessage",
      localeCode.value === "en"
        ? "You will receive the latest updates from us soon."
        : "Bạn sẽ sớm nhận được những cập nhật mới nhất từ chúng tôi."
    ),
    retry: translate(
      "footer.newsletterRetry",
      localeCode.value === "en" ? "Try another email" : "Dùng email khác"
    ),
    invalidEmail: translate(
      "footer.newsletterInvalidEmail",
      localeCode.value === "en"
        ? "Please enter a valid email address."
        : "Vui lòng nhập địa chỉ email hợp lệ."
    ),
    genericError: translate(
      "footer.newsletterError",
      localeCode.value === "en"
        ? "Unable to subscribe right now. Please try again later."
        : "Hiện chưa thể đăng ký. Vui lòng thử lại sau."
    ),
  }));

  const isEmailValid = computed(() =>
    /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())
  );

  const fieldError = computed(() => {
    const value = newsletterStore.errors.email;

    if (Array.isArray(value)) {
      return value[0] || "";
    }

    return value || "";
  });

  const submitError = computed(
    () => fieldError.value || localError.value || newsletterStore.submitError || ""
  );

  const resetState = () => {
    localError.value = "";
    submitSuccess.value = "";
    newsletterStore.errors = {};
    newsletterStore.submitError = "";
  };

  const resetForm = () => {
    email.value = "";
    resetState();
  };

  const handleSubmit = async () => {
    resetState();

    if (!isEmailValid.value) {
      localError.value = labels.value.invalidEmail;
      return;
    }

    try {
      const response = await newsletterStore.submitWidgetForm({
        email: email.value.trim(),
      });

      submitSuccess.value =
        response?.message || labels.value.successMessage;
      email.value = "";
    } catch (error: any) {
      localError.value =
        error?.data?.message ||
        error?.statusMessage ||
        newsletterStore.submitError ||
        labels.value.genericError;
    }
  };

  return {
    newsletterStore,
    email,
    labels,
    submitSuccess,
    submitError,
    handleSubmit,
    resetForm,
  };
};

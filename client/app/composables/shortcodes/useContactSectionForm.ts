import type {
  ContactFieldKey,
  ContactFormPayload,
  ContactSectionTab,
} from "~~/shared/validation/types";

export const useContactSectionForm = (sourceData: MaybeRefOrGetter<any>) => {
  const { translate } = useI18nText();
  const contactStore = useContactStore();
  const contactPanelRef = ref<HTMLElement | null>(null);
  const nameInputRef = ref<HTMLInputElement | null>(null);
  const emailInputRef = ref<HTMLInputElement | null>(null);
  const addressInputRef = ref<HTMLInputElement | null>(null);
  const phoneInputRef = ref<HTMLInputElement | null>(null);
  const subjectInputRef = ref<HTMLInputElement | null>(null);
  const contentInputRef = ref<HTMLTextAreaElement | null>(null);
  const policyInputRef = ref<HTMLInputElement | null>(null);


  const sectionData = computed(() => {
    const data = toValue(sourceData);
    const source = {
      ...(data?.items || {}),
      ...(data || {}),
      ...(data?.data || {}),
    };

    return {
      ...source,
      title: source.title || "",
      description: source.description || "",
    };
  });

  const formMeta = computed(() => ({
    title: sectionData.value.form?.title || sectionData.value.form_title || "",
    description:
      sectionData.value.form?.description || sectionData.value.form_description || "",
    buttonLabel:
      sectionData.value.form?.button_label ||
      sectionData.value.form_button_label ||
      translate("contactForm.submit", "Send"),
  }));

  const labels = computed(() => ({
    name: translate("contactForm.name", "Name"),
    email: translate("contactForm.email", "Email"),
    phone: translate("contactForm.phone", "Phone"),
    address: translate("contactForm.address", "Address"),
    subject: translate("contactForm.titleForm", "Subject"),
    message: translate("contactForm.messagePlaceholder", "Write your message here."),
    policy: translate(
      "contactForm.policy",
      "I agree to the Terms and Privacy Policy."
    ),
    submitting: translate("contactForm.submitting", "Sending..."),
  }));

  const successState = computed(() => ({
    title: translate("contactForm.successTitle", "Tin nhắn đã được gửi"),
    description: translate(
      "contactForm.successDescription",
      "Đội ngũ của chúng tôi sẽ xem xét và phản hồi bạn trong thời gian sớm nhất."
    ),
    action: translate("contactForm.sendAnother", "Gửi thêm tin nhắn"),
  }));

  const legacyTabs = computed<ContactSectionTab[]>(() => {
    const items: ContactSectionTab[] = [];

    for (let index = 1; index <= 3; index++) {
      const title = sectionData.value[`title_${index}`];
      const description = sectionData.value[`description_${index}`];

      if (!title && !description) {
        continue;
      }

      items.push({ title, description });
    }

    return items;
  });

  const informationItems = computed(() => {
    const tabs =
      Array.isArray(sectionData.value.tabs) && sectionData.value.tabs.length
        ? sectionData.value.tabs
        : legacyTabs.value;

    const fallbackIcons = [
      "solar:map-point-bold",
      "solar:phone-bold",
      "solar:letter-bold",
    ];

    return tabs.map((item: ContactSectionTab, index: number) => ({
      title: item.title || "",
      description: item.description || "",
      icon: item.icon || fallbackIcons[index] || "solar:widget-bold",
    }));
  });

  const parseFieldFlags = <T extends string>(raw: string | undefined, defaults: T[]) => {
    const entries = (raw || "")
      .split(",")
      .map((item) => item.trim())
      .filter(Boolean) as T[];

    return (entries.length ? entries : defaults).reduce<Record<string, boolean>>(
      (accumulator, field) => {
        accumulator[field] = true;
        return accumulator;
      },
      {}
    );
  };

  const displayFieldsSource = computed(() => sectionData.value.display_fields || "");
  const requiredFieldsSource = computed(() => sectionData.value.mandatory_fields || "");

  const displayFields = computed<Record<ContactFieldKey, boolean>>(() => {
    const parsed = parseFieldFlags<ContactFieldKey>(displayFieldsSource.value, [
      "phone",
      "email",
      "address",
      "subject",
    ]);

    return {
      email: !!parsed.email,
      address: !!parsed.address,
      phone: !!parsed.phone,
      subject: !!parsed.subject,
    };
  });

  const mandatoryFields = computed<Record<ContactFieldKey, boolean>>(() => {
    const parsed = parseFieldFlags<ContactFieldKey>(requiredFieldsSource.value, [
      "email",
    ]);

    return {
      email: !!parsed.email,
      address: !!parsed.address,
      phone: !!parsed.phone,
      subject: !!parsed.subject,
    };
  });

  const form = reactive({
    name: "",
    email: "",
    address: "",
    phone: "",
    subject: "",
    content: "",
    agree_terms_and_policy: false,
  });

  const submitError = ref("");
  const submitSuccess = ref("");

  const resetForm = () => {
    form.name = "";
    form.email = "";
    form.address = "";
    form.phone = "";
    form.subject = "";
    form.content = "";
    form.agree_terms_and_policy = false;
  };

  const resetValidationState = () => {
    contactStore.errors = {};
    submitError.value = "";
  };

  const handleSendAnother = async () => {
    submitSuccess.value = "";
    resetValidationState();
    await nextTick();
    nameInputRef.value?.focus();
  };

  const fieldError = (field: string) => {
    const value = contactStore.errors[field];

    if (Array.isArray(value)) {
      return value[0] || "";
    }

    return value || "";
  };

  const fieldErrorId = (field: string) => `contact-${field}-error`;

  const focusFirstInvalidField = async () => {
    await nextTick();

    const fieldRefs: Record<string, Ref<HTMLElement | null>> = {
      name: nameInputRef as Ref<HTMLElement | null>,
      email: emailInputRef as Ref<HTMLElement | null>,
      address: addressInputRef as Ref<HTMLElement | null>,
      phone: phoneInputRef as Ref<HTMLElement | null>,
      subject: subjectInputRef as Ref<HTMLElement | null>,
      content: contentInputRef as Ref<HTMLElement | null>,
      agree_terms_and_policy: policyInputRef as Ref<HTMLElement | null>,
    };

    const orderedFields = [
      "name",
      "email",
      "address",
      "phone",
      "subject",
      "content",
      "agree_terms_and_policy",
    ];

    for (const field of orderedFields) {
      if (!fieldError(field)) {
        continue;
      }

      const target = fieldRefs[field]?.value;

      if (target) {
        target.focus();
        target.scrollIntoView({
          behavior: "smooth",
          block: "center",
        });
      }

      break;
    }
  };

  const handleSubmit = async () => {
    resetValidationState();
    submitSuccess.value = "";

    try {
      const payload: ContactFormPayload = {
        name: form.name,
        email: form.email || undefined,
        address: form.address || undefined,
        phone: form.phone || undefined,
        subject: form.subject || undefined,
        content: form.content,
        agree_terms_and_policy: form.agree_terms_and_policy ? 1 : undefined,
        display_fields: displayFieldsSource.value,
        required_fields: requiredFieldsSource.value,
      };

      const response = await contactStore.submitSectionForm(payload);

      submitSuccess.value =
        response?.message ||
        translate("contactForm.success", "Send message successfully!");
      resetForm();
      await nextTick();
      contactPanelRef.value?.scrollIntoView({
        behavior: "smooth",
        block: "center",
      });
    } catch (error: any) {
      submitError.value =
        error?.data?.message ||
        error?.statusMessage ||
        contactStore.submitError ||
        translate(
          "contactForm.error",
          "Can't send message right now. Please try again later."
        );
      await focusFirstInvalidField();
    }
  };

  return {
    contactStore,
    contactPanelRef,
    nameInputRef,
    emailInputRef,
    addressInputRef,
    phoneInputRef,
    subjectInputRef,
    contentInputRef,
    policyInputRef,
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
    fieldErrorId,
    handleSubmit,
    handleSendAnother,
  };
};



import type { ContactFieldKey } from "~~/shared/types/contact";

export type ContactValidationLocale = "vi" | "en";

export const getContactValidationMessages = (locale: string) => {
  const resolvedLocale: ContactValidationLocale = locale === "en" ? "en" : "vi";

  const fieldLabels: Record<ContactValidationLocale, Record<ContactFieldKey | "name" | "content", string>> = {
    vi: {
      name: "Tên",
      email: "Email",
      phone: "S? di?n tho?i",
      address: "Ð?a ch?",
      subject: "Tiêu d?",
      content: "N?i dung",
    },
    en: {
      name: "Name",
      email: "Email",
      phone: "Phone",
      address: "Address",
      subject: "Subject",
      content: "Message",
    },
  };

  const common = {
    vi: {
      invalid: "D? li?u form chua h?p l?.",
      terms: "B?n c?n d?ng ý v?i di?u kho?n và chính sách quy?n riêng tu.",
      invalidEmail: "Email không dúng d?nh d?ng.",
      required: (field: string) => `${field} là b?t bu?c.`,
      max: (field: string, max: number) => `${field} không du?c vu?t quá ${max} ký t?.`,
    },
    en: {
      invalid: "The form data is invalid.",
      terms: "You need to agree to the Terms and Privacy Policy.",
      invalidEmail: "Email format is invalid.",
      required: (field: string) => `${field} is required.`,
      max: (field: string, max: number) => `${field} must not exceed ${max} characters.`,
    },
  } as const;

  return {
    labels: fieldLabels[resolvedLocale],
    ...common[resolvedLocale],
  };
};

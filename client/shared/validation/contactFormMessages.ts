import type { ContactFieldKey } from "~~/shared/validation/types";
import type { AppLocale } from "~~/shared/i18n/types";
import { resolveAppLocale } from "~~/shared/i18n/locale";

export type ContactValidationLocale = AppLocale;

export const getContactValidationMessages = (locale: string) => {
  const resolvedLocale = resolveAppLocale(locale);

  const fieldLabels: Record<
    ContactValidationLocale,
    Record<ContactFieldKey | "name" | "content", string>
  > = {
    vi: {
      name: "Tên",
      email: "Email",
      phone: "Số điện thoại",
      address: "Địa chỉ",
      subject: "Tiêu đề",
      content: "Nội dung",
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
      invalid: "Dữ liệu form chưa hợp lệ.",
      terms: "Bạn cần đồng ý với điều khoản và chính sách quyền riêng tư.",
      invalidEmail: "Email không đúng định dạng.",
      required: (field: string) => `${field} là bắt buộc.`,
      max: (field: string, max: number) =>
        `${field} không được vượt quá ${max} ký tự.`,
    },
    en: {
      invalid: "The form data is invalid.",
      terms: "You need to agree to the Terms and Privacy Policy.",
      invalidEmail: "Email format is invalid.",
      required: (field: string) => `${field} is required.`,
      max: (field: string, max: number) =>
        `${field} must not exceed ${max} characters.`,
    },
  } as const;

  return {
    labels: fieldLabels[resolvedLocale],
    ...common[resolvedLocale],
  };
};


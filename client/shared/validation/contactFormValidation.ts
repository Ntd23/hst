import type {
  ContactFieldKey,
  ContactFormBody,
  ContactFormErrors,
  ContactFormPayload,
} from "../types/contact";
import { getContactValidationMessages } from "./contactFormMessages";

const CONTACT_OPTIONAL_FIELDS: ContactFieldKey[] = [
  "email",
  "phone",
  "address",
  "subject",
];

const normalizeString = (value: unknown) => {
  if (typeof value !== "string") {
    return "";
  }

  return value.trim();
};

const parseFieldList = (value: unknown, fallback: ContactFieldKey[]) => {
  const normalized = normalizeString(value);

  if (!normalized) {
    return fallback;
  }

  return normalized
    .split(",")
    .map((item) => item.trim())
    .filter(Boolean) as ContactFieldKey[];
};

const isValidEmail = (value: string) => {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
};

export const validateContactFormBody = (
  body: ContactFormBody,
  locale = "vi"
): { data?: ContactFormPayload; errors?: ContactFormErrors } => {
  const errors: ContactFormErrors = {};
  const messages = getContactValidationMessages(locale);

  const name = normalizeString(body.name);
  const email = normalizeString(body.email);
  const phone = normalizeString(body.phone);
  const address = normalizeString(body.address);
  const subject = normalizeString(body.subject);
  const content = normalizeString(body.content);
  const displayFields = parseFieldList(body.display_fields, [
    "phone",
    "email",
    "address",
    "subject",
  ]);
  const requiredFields = parseFieldList(body.required_fields, ["email"]);

  if (!name) {
    errors.name = [messages.required(messages.labels.name)];
  } else if (name.length > 40) {
    errors.name = [messages.max(messages.labels.name, 40)];
  }

  if (!content) {
    errors.content = [messages.required(messages.labels.content)];
  } else if (content.length > 10000) {
    errors.content = [messages.max(messages.labels.content, 10000)];
  }

  for (const field of CONTACT_OPTIONAL_FIELDS) {
    const isDisplayed = displayFields.includes(field);
    const isRequired = requiredFields.includes(field);
    const value = { email, phone, address, subject }[field];

    if (!isDisplayed) {
      continue;
    }

    if (isRequired && !value) {
      errors[field] = [messages.required(messages.labels[field])];
      continue;
    }

    if (!value) {
      continue;
    }

    if (field === "email" && !isValidEmail(value)) {
      errors.email = [messages.invalidEmail];
    }

    if (field === "email" && value.length > 80) {
      errors.email = [messages.max(messages.labels.email, 80)];
    }

    if (field === "phone" && value.length > 30) {
      errors.phone = [messages.max(messages.labels.phone, 30)];
    }

    if (field === "address" && value.length > 500) {
      errors.address = [messages.max(messages.labels.address, 500)];
    }

    if (field === "subject" && value.length > 500) {
      errors.subject = [messages.max(messages.labels.subject, 500)];
    }
  }

  if (
    body.agree_terms_and_policy !== undefined &&
    body.agree_terms_and_policy !== 1
  ) {
    errors.agree_terms_and_policy = [messages.terms];
  }

  if (Object.keys(errors).length) {
    return { errors };
  }

  return {
    data: {
      name,
      email: email || undefined,
      phone: phone || undefined,
      address: address || undefined,
      subject: subject || undefined,
      content,
      display_fields: displayFields.join(","),
      required_fields: requiredFields.join(","),
      agree_terms_and_policy: body.agree_terms_and_policy === 1 ? 1 : undefined,
    },
  };
};

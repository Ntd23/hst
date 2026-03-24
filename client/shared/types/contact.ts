export type ContactFieldKey = "email" | "address" | "phone" | "subject";

export type ContactSectionTab = {
  title?: string | null;
  description?: string | null;
  icon?: string | null;
  icon_image?: string | null;
};

export type ContactFormBody = {
  name?: unknown;
  email?: unknown;
  phone?: unknown;
  address?: unknown;
  subject?: unknown;
  content?: unknown;
  display_fields?: unknown;
  required_fields?: unknown;
  agree_terms_and_policy?: unknown;
};

export type ContactFormPayload = {
  name: string;
  email?: string;
  phone?: string;
  address?: string;
  subject?: string;
  content: string;
  display_fields?: string;
  required_fields?: string;
  agree_terms_and_policy?: 1;
};

export type ContactFormErrors = Record<string, string[]>;

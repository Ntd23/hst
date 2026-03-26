import type { AppLocale } from "~~/shared/i18n/types";

export const resolveAppLocale = (locale?: string | null): AppLocale =>
  locale === "en" ? "en" : "vi";


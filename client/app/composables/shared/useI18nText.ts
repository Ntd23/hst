import { resolveAppLocale } from "~~/shared/i18n/locale";

export const useI18nText = () => {
  const { t, locale, locales } = useI18n();

  const localeCode = computed(() => resolveAppLocale(locale.value));
  const availableLocales = computed(
    () => locales.value as Array<{ code: string; name: string }>
  );

  const translate = (key: string, fallback = "") => {
    const value = t(key);
    return value === key ? fallback : String(value);
  };

  return {
    t,
    locale,
    localeCode,
    locales: availableLocales,
    translate,
  };
};


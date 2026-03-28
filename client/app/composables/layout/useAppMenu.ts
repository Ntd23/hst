import type { MenuItem } from "~~/shared/navigation/types";
import { flattenMenuItems } from "~~/shared/navigation/menu";
import { resolveAppLocale } from "~~/shared/i18n/locale";
import { useMenus } from "~/composables/layout/useMenus";

export const useAppMenu = () => {
  const isScrolled = ref(false);
  const isMobileMenuOpen = ref(false);
  const activeDropdown = ref<string | number | null>(null);

  const { locale, locales: availableLocales } = useI18nText();
  const switchLocalePath = useSwitchLocalePath();
  const { menuData } = useMenus();

  const computedNavItems = computed(
    () => (menuData.value?.main_menu?.items ?? []) as MenuItem[]
  );

  const switchLocale = (code: string) => {
    const path = switchLocalePath(resolveAppLocale(code));

    if (import.meta.client) {
      window.location.href = path;
    }
  };

  const contactButtonLink = computed(() => {
    const menuItems = flattenMenuItems(computedNavItems.value);
    const contactItem = menuItems.find((item) => {
      const title = (item.title || item.label || "").toLowerCase().trim();
      const url = (item.url || item.to || "").toLowerCase().trim();

      return (
        title.includes("liên h?") ||
        title.includes("lien he") ||
        title.includes("contact") ||
        url.includes("lien-he") ||
        url.includes("contact")
      );
    });

    return contactItem?.url || contactItem?.to || "/contact-us";
  });

  const handleScroll = () => {
    isScrolled.value = window.scrollY > 12;
  };

  const handleResize = () => {
    if (window.innerWidth >= 1024) {
      isMobileMenuOpen.value = false;
    }
  };

  onMounted(() => {
    handleScroll();
    handleResize();
    window.addEventListener("scroll", handleScroll, { passive: true });
    window.addEventListener("resize", handleResize, { passive: true });
  });

  onBeforeUnmount(() => {
    window.removeEventListener("scroll", handleScroll);
    window.removeEventListener("resize", handleResize);
  });

  return {
    isScrolled,
    isMobileMenuOpen,
    activeDropdown,
    locale,
    availableLocales,
    menuData,
    computedNavItems,
    contactButtonLink,
    switchLocale,
  };
};

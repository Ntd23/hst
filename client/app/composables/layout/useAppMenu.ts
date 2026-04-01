import type { MenuItem } from "~~/shared/navigation/types";
import { flattenMenuItems } from "~~/shared/navigation/menu";
import { resolveAppLocale } from "~~/shared/i18n/locale";
import { useMenus } from "~/composables/layout/useMenus";
import { useLayoutWidgets } from "~/composables/layout/useLayoutWidgets";

type ContactInfoItem = {
  title?: string;
  icon?: string;
  icon_image?: string | null;
  url?: string;
};

type SocialItem = {
  network?: string;
  label?: string;
  url?: string;
  icon?: string;
};

export const useAppMenu = () => {
  const isScrolled = ref(false);
  const isMobileMenuOpen = ref(false);
  const activeDropdown = ref<string | number | null>(null);
  const activeMobileDropdown = ref<string | number | null>(null);

  const { locale, locales: availableLocales, localeCode } = useI18nText();
  const switchLocalePath = useSwitchLocalePath();
  const { menuData, pending: menuPending } = useMenus();
  const { layoutWidgetData, pending: layoutPending } = useLayoutWidgets();
  const isReady = computed(
    () =>
      !menuPending.value &&
      !layoutPending.value &&
      Boolean(menuData.value) &&
      Boolean(layoutWidgetData.value)
  );

  const computedNavItems = computed(
    () => (menuData.value?.main_menu?.items ?? []) as MenuItem[]
  );

  const resolveSectionItems = (section: any) => section?.items ?? [];

  const headerStartItems = computed<ContactInfoItem[]>(() => {
    const widget = resolveSectionItems(layoutWidgetData.value?.header_top_start)[0];
    return widget?.content?.items ?? [];
  });

  const headerEndItems = computed<ContactInfoItem[]>(() => {
    const widget = resolveSectionItems(layoutWidgetData.value?.header_top_end)[0];
    return widget?.content?.items ?? [];
  });

  const menuSidebarItems = computed(() => resolveSectionItems(layoutWidgetData.value?.menu_sidebar));

  const menuSidebarSocials = computed<SocialItem[]>(() =>
    menuSidebarItems.value.find((item: any) => item.widget === "social-links")?.content?.socials ?? []
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
      activeMobileDropdown.value = null;
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
    activeMobileDropdown,
    isReady,
    locale,
    availableLocales,
    menuData,
    computedNavItems,
    headerStartItems,
    headerEndItems,
    menuSidebarSocials,
    contactButtonLink,
    switchLocale,
  };
};

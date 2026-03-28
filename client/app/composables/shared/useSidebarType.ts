export type SidebarWidgetType =
  | "primary"
  | "blog"
  | "service"
  | "product";

export const useSidebarType = () => {
  const resolveSidebarTypeFromPage = (
    pageType?: string | null
  ): SidebarWidgetType => {
    switch (pageType) {
      case "blog":
        return "blog";
      case "service":
        return "service";
      case "product":
        return "product";
      default:
        return "primary";
    }
  };

  return {
    resolveSidebarTypeFromPage,
  };
};

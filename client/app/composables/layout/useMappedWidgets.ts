import { markRaw } from "vue";
import { getWidgetComponents } from "~/utils/getWidgetComponents";

export const useMappedWidgets = () => {
  const components = getWidgetComponents();

  const mapWidgets = (sections: any[] = []) => {
    if (!Array.isArray(sections)) {
      return [];
    }

    return sections.map((section: any) => {
      const widget = section.widget;
      const formattedName = widget
        .split("-")
        .map((item: string) => item.charAt(0).toUpperCase() + item.slice(1))
        .join("");
      const component = components[`Widget${formattedName}`] || "div";

      return {
        component: typeof component === "string" ? component : markRaw(component),
        data: section.content,
        meta: section,
      };
    });
  };

  return {
    mapWidgets,
  };
};

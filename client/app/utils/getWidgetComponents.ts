export function getWidgetComponents() {
  const modules = import.meta.glob("@/components/widget/*.vue", {
    eager: true,
  });

  const components: Record<string, any> = {};

  for (const path in modules) {
    const name = path.split("/").pop()?.replace(".vue", "");

    if (!name) continue;

    const componentName =
      "Widget" +
      name
        .split("-")
        .map((part) => part.charAt(0).toUpperCase() + part.slice(1))
        .join("");

    components[componentName] = (modules[path] as any).default;
  }

  return components;
}

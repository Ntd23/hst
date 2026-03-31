export const iconName = (icon?: string | null) => {
  if (!icon) {
    return "i-tabler-help-circle";
  }

  if (icon.startsWith("i-")) {
    return icon;
  }

  if (icon.startsWith("ti ti-")) {
    return `i-tabler-${icon.replace(/^ti ti-/, "")}`;
  }

  if (icon.startsWith("ti-")) {
    return `i-tabler-${icon.replace(/^ti-/, "")}`;
  }

  return icon;
};

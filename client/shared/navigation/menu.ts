import type { MenuItem } from "~~/shared/navigation/types";

export const normalizePath = (value: string | undefined | null): string => {
  if (!value) return "/";

  let path = value.trim();
  if (!path.startsWith("/")) path = `/${path}`;
  path = path.replace(/\/{2,}/g, "/");
  if (path.length > 1) path = path.replace(/\/+$/, "");
  return path.toLowerCase();
};

export const flattenMenuItems = (items: MenuItem[]): MenuItem[] => {
  return items.flatMap((item) => [item, ...flattenMenuItems(item.children ?? [])]);
};


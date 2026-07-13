<template>
  <span
    v-if="iconBody"
    v-bind="$attrs"
    class="botble-icon"
    aria-hidden="true"
  >
    <svg
      xmlns="http://www.w3.org/2000/svg"
      :viewBox="iconViewBox"
      v-html="iconBody"
    />
  </span>
  <UIcon
    v-else
    :name="resolvedIconName"
    v-bind="$attrs"
    aria-hidden="true"
  />
</template>

<script setup lang="ts">
import heroiconsIcons from "@iconify-json/heroicons/icons.json";
import lucideIcons from "@iconify-json/lucide/icons.json";
import simpleIcons from "@iconify-json/simple-icons/icons.json";
import tablerIcons from "@iconify-json/tabler/icons.json";

defineOptions({
  inheritAttrs: false,
});

const props = defineProps<{
  icon?: string | null;
}>();

type IconCollectionData = {
  prefix?: string;
  width?: number;
  height?: number;
  icons: Record<string, { body?: string; width?: number; height?: number; parent?: string }>;
  aliases?: Record<string, { parent?: string; body?: string; width?: number; height?: number }>;
};

type NormalizedIcon = {
  collection: "tabler" | "lucide" | "heroicons" | "simple-icons";
  name: string;
};

const LOCAL_COLLECTIONS: Record<NormalizedIcon["collection"], IconCollectionData> = {
  tabler: tablerIcons as IconCollectionData,
  lucide: lucideIcons as IconCollectionData,
  heroicons: heroiconsIcons as IconCollectionData,
  "simple-icons": simpleIcons as IconCollectionData,
};

const ICON_ALIASES: Record<string, string> = {
  "solar:map-point-bold": "i-lucide-map-pin",
  "solar:phone-bold": "i-lucide-phone-call",
  "solar:letter-bold": "i-lucide-mail",
  "solar:check-circle-bold": "i-heroicons-check-circle-20-solid",
  "solar:calendar-broken": "i-lucide-calendar",
  "i-lucide-map-pinned": "i-lucide-map-pin",
};

const getCanonicalIcon = (icon?: string | null) => {
  if (!icon) {
    return null;
  }

  return ICON_ALIASES[icon] || icon;
};

const normalizeLocalIcon = (icon?: string | null): NormalizedIcon | null => {
  const canonicalIcon = getCanonicalIcon(icon);

  if (!canonicalIcon) {
    return null;
  }

  if (canonicalIcon.startsWith("ti ti-")) {
    return {
      collection: "tabler",
      name: canonicalIcon.replace(/^ti ti-/, "").trim(),
    };
  }

  if (canonicalIcon.startsWith("ti-")) {
    return {
      collection: "tabler",
      name: canonicalIcon.replace(/^ti-/, "").trim(),
    };
  }

  if (canonicalIcon.startsWith("i-tabler-")) {
    return {
      collection: "tabler",
      name: canonicalIcon.replace(/^i-tabler-/, "").trim(),
    };
  }

  if (canonicalIcon.startsWith("i-lucide-")) {
    return {
      collection: "lucide",
      name: canonicalIcon.replace(/^i-lucide-/, "").trim(),
    };
  }

  if (canonicalIcon.startsWith("i-heroicons-")) {
    return {
      collection: "heroicons",
      name: canonicalIcon.replace(/^i-heroicons-/, "").trim(),
    };
  }

  if (canonicalIcon.startsWith("i-simple-icons-")) {
    return {
      collection: "simple-icons",
      name: canonicalIcon.replace(/^i-simple-icons-/, "").trim(),
    };
  }

  if (canonicalIcon.startsWith("tabler:")) {
    return {
      collection: "tabler",
      name: canonicalIcon.replace(/^tabler:/, "").trim(),
    };
  }

  if (canonicalIcon.startsWith("lucide:")) {
    return {
      collection: "lucide",
      name: canonicalIcon.replace(/^lucide:/, "").trim(),
    };
  }

  if (canonicalIcon.startsWith("heroicons:")) {
    return {
      collection: "heroicons",
      name: canonicalIcon.replace(/^heroicons:/, "").trim(),
    };
  }

  if (canonicalIcon.startsWith("simple-icons:")) {
    return {
      collection: "simple-icons",
      name: canonicalIcon.replace(/^simple-icons:/, "").trim(),
    };
  }

  return null;
};

const resolveLocalIconEntry = (normalized: NormalizedIcon | null) => {
  if (!normalized) {
    return null;
  }

  const collection = LOCAL_COLLECTIONS[normalized.collection];
  if (!collection) {
    return null;
  }

  const visited = new Set<string>();
  let currentName = normalized.name;

  while (currentName && !visited.has(currentName)) {
    visited.add(currentName);

    const iconEntry = collection.icons[currentName];
    if (iconEntry?.body) {
      return {
        body: iconEntry.body,
        width: iconEntry.width || collection.width || 24,
        height: iconEntry.height || collection.height || 24,
      };
    }

    const aliasEntry = collection.aliases?.[currentName];
    if (aliasEntry?.body) {
      return {
        body: aliasEntry.body,
        width: aliasEntry.width || collection.width || 24,
        height: aliasEntry.height || collection.height || 24,
      };
    }

    currentName = aliasEntry?.parent || iconEntry?.parent || "";
  }

  return null;
};

const resolvedIconName = computed(() => {
  const canonicalIcon = getCanonicalIcon(props.icon);

  if (!canonicalIcon) {
    return "i-lucide-circle-help";
  }

  if (resolveLocalIconEntry(normalizeLocalIcon(canonicalIcon))) {
    return null;
  }

  if (canonicalIcon.startsWith("i-")) {
    return canonicalIcon;
  }

  if (canonicalIcon.includes(":")) {
    return canonicalIcon;
  }

  return "i-lucide-circle-help";
});

const resolvedLocalIcon = computed(() =>
  resolveLocalIconEntry(normalizeLocalIcon(getCanonicalIcon(props.icon)))
);

const iconBody = computed(() => {
  return resolvedLocalIcon.value?.body ?? null;
});

const iconViewBox = computed(() => {
  const width = resolvedLocalIcon.value?.width || 24;
  const height = resolvedLocalIcon.value?.height || 24;

  return `0 0 ${width} ${height}`;
});
</script>

<style scoped>
.botble-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  line-height: 1;
  vertical-align: middle;
}

.botble-icon :deep(svg) {
  display: block;
  width: 100%;
  height: 100%;
}
</style>

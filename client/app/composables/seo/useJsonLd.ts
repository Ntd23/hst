import { computed, toValue } from "vue";
import type { MaybeRefOrGetter } from "vue";

type JsonLdNode = Record<string, any>;

type JsonLdInput = JsonLdNode | JsonLdNode[] | null | undefined;

export const useJsonLd = (schema: MaybeRefOrGetter<JsonLdInput>) => {
  const resolvedSchema = computed(() => {
    const value = toValue(schema);

    if (!value) {
      return [] as JsonLdNode[];
    }

    return Array.isArray(value) ? value.filter(Boolean) : [value];
  });

  useHead(() => ({
    script: resolvedSchema.value.map((item, index) => ({
      key: `json-ld-${item['@type'] || 'schema'}-${index}`,
      type: 'application/ld+json',
      textContent: JSON.stringify(item),
    })),
  }));
};

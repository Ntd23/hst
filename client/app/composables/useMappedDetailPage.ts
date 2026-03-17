import { computed, markRaw, unref, type MaybeRef } from 'vue'
import { getDetailComponents } from '~/utils/getDetailComponents'

export function useMappedDetailPage(page: MaybeRef<string>) {
  const components = getDetailComponents()
  console.log("đây là components", components);


  const detailComponent = computed(() => {
    const key = unref(page)

    if (!key) return null

    const normalized = String(key)
      .toLowerCase()
      .replace(/s$/, '')


    const comp = components[normalized]
    console.log("đây là normalized", normalized);

    console.log("đây là comp", comp);


    return comp ? markRaw(comp) : null
  })

  return {
    detailComponent,
  }
}